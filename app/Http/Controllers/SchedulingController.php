<?php

namespace App\Http\Controllers;

use App\Services\SchedulingService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SchedulingController extends Controller
{
    public function preview(Request $request, int $employeeId)
    {
        $date = $request->query('date') ? Carbon::parse($request->query('date')) : Carbon::today();
        $service = new SchedulingService();
        $resolved = $service->resolveShiftForDate($employeeId, $date);
        return response()->json([
            'employee_id' => $employeeId,
            'date' => $date->toDateString(),
            'shift' => $resolved['shift'],
            'window_start' => optional($resolved['window_start'])->toDateTimeString(),
            'window_end' => optional($resolved['window_end'])->toDateTimeString(),
        ]);
    }
}


