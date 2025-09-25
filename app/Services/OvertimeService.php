<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\OvertimeEntry;
use App\Models\OvertimeRule;
use Carbon\Carbon;

class OvertimeService
{
    private SchedulingService $schedulingService;

    public function __construct()
    {
        $this->schedulingService = new SchedulingService();
    }

    /**
     * Calculate overtime for all employees with attendance in the range.
     * Simple rules: minutes before shift start = pre_shift; after shift end = post_shift;
     * If holiday, classify all presence minutes as holiday.
     */
    public function calculateRange(Carbon $startDate, Carbon $endDate): int
    {
        $start = $startDate->copy()->startOfDay();
        $end = $endDate->copy()->endOfDay();

        $globalRule = OvertimeRule::where('scope', 'global')->orderByDesc('id')->first();
        $minThreshold = $globalRule->min_minutes_threshold ?? 30;
        $rounding = $globalRule->rounding_minutes ?? 15;

        $att = DB::table('attendances')
            ->select('employee_id', DB::raw('DATE(`timestamp`) as d'), DB::raw('MIN(`timestamp`) as first_ts'), DB::raw('MAX(`timestamp`) as last_ts'))
            ->whereBetween('timestamp', [$start, $end])
            ->groupBy('employee_id', DB::raw('DATE(`timestamp`)'))
            ->get();

        $inserted = 0;
        foreach ($att as $row) {
            $employeeId = (int) $row->employee_id;
            $date = Carbon::parse($row->d);
            $first = Carbon::parse($row->first_ts);
            $last = Carbon::parse($row->last_ts);

            // Resolve shift for this date
            $resolved = $this->schedulingService->resolveShiftForDate($employeeId, $date);
            $shift = $resolved['shift'];
            $windowStart = $resolved['window_start'];
            $windowEnd = $resolved['window_end'];

            // Check holiday
            $isHoliday = DB::table('holidays')
                ->whereDate('date', $date->toDateString())
                ->exists();

            if ($isHoliday) {
                $minutes = $first->diffInMinutes($last);
                $minutes = $this->roundAndThreshold($minutes, $rounding, $minThreshold);
                if ($minutes > 0) {
                    $inserted += $this->upsertOvertime($employeeId, $date, $minutes, 'holiday');
                }
                continue;
            }

            if (!$shift || !$windowStart || !$windowEnd) {
                continue; // no shift context; skip for now
            }

            $pre = 0;
            $post = 0;
            if ($first->lessThan($windowStart)) {
                $pre = $first->diffInMinutes($windowStart);
            }
            if ($last->greaterThan($windowEnd)) {
                $post = $windowEnd->diffInMinutes($last);
            }

            $pre = $this->roundAndThreshold($pre, $rounding, $minThreshold);
            $post = $this->roundAndThreshold($post, $rounding, $minThreshold);

            if ($pre > 0) {
                $inserted += $this->upsertOvertime($employeeId, $date, $pre, 'pre_shift');
            }
            if ($post > 0) {
                $inserted += $this->upsertOvertime($employeeId, $date, $post, 'post_shift');
            }
        }

        return $inserted;
    }

    private function roundAndThreshold(int $minutes, int $rounding, int $threshold): int
    {
        if ($minutes < $threshold) {
            return 0;
        }
        if ($rounding > 1) {
            $minutes = (int) (ceil($minutes / $rounding) * $rounding);
        }
        return $minutes;
    }

    private function upsertOvertime(int $employeeId, Carbon $date, int $minutes, string $type): int
    {
        $existing = OvertimeEntry::where('employee_id', $employeeId)
            ->whereDate('date', $date->toDateString())
            ->where('type', $type)
            ->first();

        if ($existing) {
            $existing->minutes = $minutes;
            $existing->save();
            return 0;
        }

        OvertimeEntry::create([
            'employee_id' => $employeeId,
            'date' => $date->toDateString(),
            'minutes' => $minutes,
            'type' => $type,
        ]);
        return 1;
    }
}


