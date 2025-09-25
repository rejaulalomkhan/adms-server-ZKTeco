<?php

namespace App\Services;

use App\Models\Shift;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SchedulingService
{
    /**
     * Resolve the applicable shift for an employee on a given date.
     * Returns array with keys: shift (Shift|null), window_start (Carbon|null), window_end (Carbon|null)
     */
    public function resolveShiftForDate(int $employeeId, Carbon $date): array
    {
        $dateOnly = $date->copy()->startOfDay();

        // 1) Manual assignment (highest priority, latest record wins when overlapping)
        $assignment = DB::table('shift_assignments')
            ->where('employee_id', $employeeId)
            ->whereDate('start_date', '<=', $dateOnly->toDateString())
            ->where(function ($q) use ($dateOnly) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $dateOnly->toDateString());
            })
            ->orderByDesc('priority')
            ->orderByDesc('id')
            ->first();

        if ($assignment) {
            $shift = Shift::find($assignment->shift_id);
            return $this->buildShiftResolution($shift, $dateOnly);
        }

        // 2) Rotation-based resolution
        $rotation = DB::table('shift_rotations')
            ->where('employee_id', $employeeId)
            ->where(function ($q) use ($dateOnly) {
                $q->whereNull('effective_date')->orWhereDate('effective_date', '<=', $dateOnly->toDateString());
            })
            ->where(function ($q) use ($dateOnly) {
                $q->whereNull('expiry_date')->orWhereDate('expiry_date', '>=', $dateOnly->toDateString());
            })
            ->orderByDesc('effective_date')
            ->orderByDesc('id')
            ->first();

        if ($rotation) {
            $effective = $rotation->effective_date ? Carbon::parse($rotation->effective_date) : $dateOnly->copy()->startOfWeek();
            $weeksBetween = (int) floor($effective->diffInWeeks($dateOnly));
            $cycleLength = max(1, (int) $rotation->cycle_length_weeks);
            $weekIndex = ($weeksBetween % $cycleLength) + 1; // 1-based

            $rotationWeek = DB::table('shift_rotation_weeks')
                ->where('rotation_id', $rotation->id)
                ->where('week_index', $weekIndex)
                ->first();
            if ($rotationWeek) {
                $shift = Shift::find($rotationWeek->shift_id);
                return $this->buildShiftResolution($shift, $dateOnly);
            }
        }

        return [
            'shift' => null,
            'window_start' => null,
            'window_end' => null,
        ];
    }

    /**
     * Build window start/end for a shift on a base date.
     */
    private function buildShiftResolution(?Shift $shift, Carbon $dateOnly): array
    {
        if (!$shift) {
            return ['shift' => null, 'window_start' => null, 'window_end' => null];
        }
        $start = Carbon::parse($dateOnly->toDateString().' '.$shift->start_time);
        $end = Carbon::parse($dateOnly->toDateString().' '.$shift->end_time);
        if ($shift->is_overnight || $end->lessThanOrEqualTo($start)) {
            $end = $end->addDay();
        }
        return [
            'shift' => $shift,
            'window_start' => $start,
            'window_end' => $end,
        ];
    }
}


