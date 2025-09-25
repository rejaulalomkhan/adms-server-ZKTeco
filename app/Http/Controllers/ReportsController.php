<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReportsController extends Controller
{
    public function index()
    {
        $data['lable'] = 'Reports';
        return view('reports.index', $data);
    }

    public function attendanceData(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $rows = DB::table('attendances')
            ->leftJoin('users', 'users.id', '=', 'attendances.employee_id')
            ->select(
                'attendances.employee_id',
                DB::raw('COALESCE(users.name, attendances.employee_id) as employee_name'),
                DB::raw('DATE(attendances.timestamp) as work_date'),
                DB::raw('MIN(attendances.timestamp) as first_in'),
                DB::raw('MAX(attendances.timestamp) as last_out'),
                DB::raw('COUNT(*) as punches')
            )
            ->whereBetween('attendances.timestamp', [
                $validated['start_date'].' 00:00:00',
                $validated['end_date'].' 23:59:59'
            ])
            ->groupBy('attendances.employee_id', DB::raw('DATE(attendances.timestamp)'), 'users.name')
            ->orderBy('work_date', 'desc')
            ->get();

        return DataTables::of($rows)->make(true);
    }

    public function latenessData(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $rows = DB::table('attendances')
            ->leftJoin('users', 'users.id', '=', 'attendances.employee_id')
            ->select(
                'attendances.employee_id',
                DB::raw('COALESCE(users.name, attendances.employee_id) as employee_name'),
                DB::raw('DATE(attendances.timestamp) as work_date'),
                DB::raw('MIN(attendances.timestamp) as first_in'),
                DB::raw("TIMESTAMPDIFF(MINUTE, CONCAT(DATE(attendances.timestamp), ' 09:00:00'), MIN(attendances.timestamp)) as late_minutes")
            )
            ->whereBetween('attendances.timestamp', [
                $validated['start_date'].' 00:00:00',
                $validated['end_date'].' 23:59:59'
            ])
            ->groupBy('attendances.employee_id', DB::raw('DATE(attendances.timestamp)'), 'users.name')
            ->havingRaw("TIME(MIN(attendances.timestamp)) > '09:15:00'")
            ->orderBy('work_date', 'desc')
            ->get();

        return DataTables::of($rows)->make(true);
    }

    public function absenceData(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);
        $date = $validated['date'];

        $presentIds = DB::table('attendances')
            ->whereBetween('timestamp', [$date.' 00:00:00', $date.' 23:59:59'])
            ->distinct()
            ->pluck('employee_id')
            ->all();

        $rows = DB::table('users')
            ->select('users.id as employee_id', 'users.name as employee_name')
            ->when(count($presentIds) > 0, function($q) use ($presentIds){
                $q->whereNotIn('users.id', $presentIds);
            })
            ->orderBy('users.name')
            ->get();

        return DataTables::of($rows)->make(true);
    }
}


