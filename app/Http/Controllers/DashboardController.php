<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $range = $request->query('range', 'today');
        $dateParam = $request->query('date');
        if ($dateParam) {
            $day = Carbon::parse($dateParam);
            $start = $day->copy()->startOfDay();
            $end = $day->copy()->endOfDay();
        } else {
            [$start, $end] = $this->resolveRange($range);
        }

        $totalEmployees = DB::table('users')->count();
        $present = DB::table('attendances')
            ->whereBetween('timestamp', [$start, $end])
            ->distinct('employee_id')
            ->count('employee_id');

        // Late: naive proxy using timestamp after 15 min past 09:00 (will refine with shift logic later)
        $late = DB::table('attendances')
            ->whereBetween('timestamp', [$start, $end])
            ->whereRaw("TIME(`timestamp`) > '09:15:00'")
            ->distinct('employee_id')
            ->count('employee_id');

        $absent = max(0, $totalEmployees - $present);

        return response()->json([
            'range' => $range,
            'start' => $start->toDateTimeString(),
            'end' => $end->toDateTimeString(),
            'totalEmployees' => $totalEmployees,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
        ]);
    }

    public function recentAttendance(Request $request)
    {
        $limit = (int) $request->query('limit', 20);
        $limit = max(1, min(100, $limit));

        $rows = DB::table('attendances')
            ->leftJoin('users', 'users.id', '=', 'attendances.employee_id')
            ->leftJoin('offices', 'offices.id', '=', 'users.office_id')
            ->select(
                'attendances.id',
                'attendances.employee_id',
                'users.name as employee_name',
                'users.profile_image as employee_profile_image',
                'offices.name as employee_designation',
                'attendances.timestamp',
                'attendances.sn'
            )
            ->orderBy('attendances.timestamp', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($rows);
    }

    private function resolveRange(string $range): array
    {
        $now = Carbon::now();
        switch ($range) {
            case 'yesterday':
                $start = $now->copy()->subDay()->startOfDay();
                $end = $now->copy()->subDay()->endOfDay();
                break;
            case 'this_week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'this_month':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'today':
            default:
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
        }
        return [$start, $end];
    }
}


