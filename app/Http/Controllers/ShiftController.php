<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShiftController extends Controller
{
    public function index()
    {
        $data['lable'] = 'Shifts';
        return view('shifts.index', $data);
    }

    public function data(Request $request)
    {
        $query = Shift::query();
        return DataTables::of($query)
            ->addColumn('action', function (Shift $shift) {
                $editUrl = route('shifts.edit', $shift->id);
                $deleteUrl = route('shifts.destroy', $shift->id);
                return view('shifts.partials.actions', compact('editUrl', 'deleteUrl', 'shift'))->render();
            })
            ->editColumn('active', function (Shift $shift) {
                return $shift->active ? 'Yes' : 'No';
            })
            ->make(true);
    }

    public function create()
    {
        return view('shifts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'is_overnight' => 'nullable|boolean',
            'break_minutes' => 'nullable|integer|min:0|max:1440',
            'grace_minutes' => 'nullable|integer|min:0|max:120',
            'expected_hours' => 'nullable|integer|min:0|max:24',
            'active' => 'nullable|boolean',
        ]);

        $validated['is_overnight'] = (bool) $request->boolean('is_overnight');
        $validated['active'] = (bool) $request->boolean('active');
        $validated['break_minutes'] = $validated['break_minutes'] ?? 0;
        $validated['grace_minutes'] = $validated['grace_minutes'] ?? 0;

        Shift::create($validated);
        return redirect()->route('shifts.index')->with('success', 'Shift created');
    }

    public function edit(Shift $shift)
    {
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'is_overnight' => 'nullable|boolean',
            'break_minutes' => 'nullable|integer|min:0|max:1440',
            'grace_minutes' => 'nullable|integer|min:0|max:120',
            'expected_hours' => 'nullable|integer|min:0|max:24',
            'active' => 'nullable|boolean',
        ]);

        $validated['is_overnight'] = (bool) $request->boolean('is_overnight');
        $validated['active'] = (bool) $request->boolean('active');
        $validated['break_minutes'] = $validated['break_minutes'] ?? 0;
        $validated['grace_minutes'] = $validated['grace_minutes'] ?? 0;

        $shift->update($validated);
        return redirect()->route('shifts.index')->with('success', 'Shift updated');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        return redirect()->route('shifts.index')->with('success', 'Shift deleted');
    }
}


