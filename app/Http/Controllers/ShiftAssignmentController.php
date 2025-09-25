<?php

namespace App\Http\Controllers;

use App\Models\ShiftAssignment;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShiftAssignmentController extends Controller
{
    public function index()
    {
        $data['lable'] = 'Manual Shift Assignments';
        $data['assignments'] = ShiftAssignment::with(['user','shift'])->orderBy('id','desc')->get();
        return view('shift_assignments.index', $data);
    }

    public function data(Request $request)
    {
        $query = ShiftAssignment::query()->with(['user','shift']);
        return DataTables::of($query)
            ->addColumn('employee', function (ShiftAssignment $a) { return optional($a->user)->name; })
            ->addColumn('shift_name', function (ShiftAssignment $a) { return optional($a->shift)->name; })
            ->addColumn('action', function (ShiftAssignment $a) {
                $editUrl = route('shift-assignments.edit', $a->id);
                $deleteUrl = route('shift-assignments.destroy', $a->id);
                return view('shift_assignments.partials.actions', compact('editUrl', 'deleteUrl', 'a'))->render();
            })
            ->make(true);
    }

    public function create()
    {
        $users = User::orderBy('name')->get(['id','name']);
        $shifts = Shift::where('active', true)->orderBy('name')->get(['id','name']);
        return view('shift_assignments.create', compact('users','shifts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'nullable|integer|min:1|max:255',
            'reason' => 'nullable|string|max:255',
        ]);
        $validated['priority'] = $validated['priority'] ?? 1;
        ShiftAssignment::create($validated);
        return redirect()->route('shift-assignments.index')->with('success', 'Assignment created');
    }

    public function edit(ShiftAssignment $shift_assignment)
    {
        $assignment = $shift_assignment;
        $users = User::orderBy('name')->get(['id','name']);
        $shifts = Shift::where('active', true)->orderBy('name')->get(['id','name']);
        return view('shift_assignments.edit', compact('assignment','users','shifts'));
    }

    public function update(Request $request, ShiftAssignment $shift_assignment)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'shift_id' => 'required|exists:shifts,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'nullable|integer|min:1|max:255',
            'reason' => 'nullable|string|max:255',
        ]);
        $validated['priority'] = $validated['priority'] ?? 1;
        $shift_assignment->update($validated);
        return redirect()->route('shift-assignments.index')->with('success', 'Assignment updated');
    }

    public function destroy(ShiftAssignment $shift_assignment)
    {
        $shift_assignment->delete();
        return redirect()->route('shift-assignments.index')->with('success', 'Assignment deleted');
    }
}


