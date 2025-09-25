<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\OvertimeEntry;
use App\Services\OvertimeService;
use Carbon\Carbon;

class OvertimeController extends Controller
{
    public function index()
    {
        $data['lable'] = 'Overtime';
        return view('overtime.index', $data);
    }

    public function data(Request $request)
    {
        $start = $request->query('start_date');
        $end = $request->query('end_date');
        $q = OvertimeEntry::with('employee','approver')->orderBy('date','desc');
        if ($start) { $q->whereDate('date','>=',$start); }
        if ($end) { $q->whereDate('date','<=',$end); }
        return DataTables::of($q)
            ->addColumn('employee', function(OvertimeEntry $e){ return optional($e->employee)->name; })
            ->addColumn('approved_by_name', function(OvertimeEntry $e){ return optional($e->approver)->name; })
            ->addColumn('action', function(OvertimeEntry $e){
                $approveUrl = route('overtime.approve', $e->id);
                return view('overtime.partials.actions', compact('approveUrl','e'))->render();
            })
            ->make(true);
    }

    public function calculate(Request $request, OvertimeService $service)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        $count = $service->calculateRange(Carbon::parse($validated['start_date']), Carbon::parse($validated['end_date']));
        return back()->with('success', "Calculated/updated {$count} overtime entries");
    }

    public function approve(Request $request, OvertimeEntry $overtime)
    {
        $overtime->approved_by = Auth::id();
        $overtime->approved_at = now();
        $overtime->save();
        return back()->with('success', 'Overtime approved');
    }
}


