<?php

namespace App\Http\Controllers;

use App\Models\ShiftRotation;
use App\Models\ShiftRotationWeek;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ShiftRotationController extends Controller
{
    public function index()
    {
        $data['lable'] = 'Shift Rotations';
        $data['rotations'] = ShiftRotation::with('user')->orderBy('id','desc')->get();
        return view('shift_rotations.index', $data);
    }

    public function data(Request $request)
    {
        $query = ShiftRotation::query()->with('user');
        return DataTables::of($query)
            ->addColumn('employee', function (ShiftRotation $rotation) {
                return optional($rotation->user)->name ?? '-';
            })
            ->addColumn('action', function (ShiftRotation $rotation) {
                $editUrl = route('shift-rotations.edit', $rotation->id);
                $deleteUrl = route('shift-rotations.destroy', $rotation->id);
                return view('shift_rotations.partials.actions', compact('editUrl', 'deleteUrl', 'rotation'))->render();
            })
            ->make(true);
    }

    public function create()
    {
        $users = User::orderBy('name')->get(['id','name']);
        $shifts = Shift::where('active', true)->orderBy('name')->get(['id','name']);
        return view('shift_rotations.create', compact('users', 'shifts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|exists:users,id',
            'cycle_length_weeks' => 'required|integer|min:1|max:8',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:effective_date',
            'weeks' => 'required|array',
            'weeks.*.week_index' => 'required|integer|min:1',
            'weeks.*.shift_id' => 'required|exists:shifts,id',
        ]);

        DB::transaction(function () use ($validated) {
            $rotation = ShiftRotation::create([
                'employee_id' => $validated['employee_id'] ?? null,
                'cycle_length_weeks' => $validated['cycle_length_weeks'],
                'effective_date' => $validated['effective_date'] ?? null,
                'expiry_date' => $validated['expiry_date'] ?? null,
            ]);

            foreach ($validated['weeks'] as $week) {
                ShiftRotationWeek::create([
                    'rotation_id' => $rotation->id,
                    'week_index' => $week['week_index'],
                    'shift_id' => $week['shift_id'],
                ]);
            }
        });

        return redirect()->route('shift-rotations.index')->with('success', 'Rotation created');
    }

    public function edit(ShiftRotation $shift_rotation)
    {
        $rotation = $shift_rotation;
        $users = User::orderBy('name')->get(['id','name']);
        $shifts = Shift::where('active', true)->orderBy('name')->get(['id','name']);
        $weeks = ShiftRotationWeek::where('rotation_id', $rotation->id)->orderBy('week_index')->get();
        return view('shift_rotations.edit', compact('rotation', 'users', 'shifts', 'weeks'));
    }

    public function update(Request $request, ShiftRotation $shift_rotation)
    {
        $validated = $request->validate([
            'employee_id' => 'nullable|exists:users,id',
            'cycle_length_weeks' => 'required|integer|min:1|max:8',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:effective_date',
            'weeks' => 'required|array',
            'weeks.*.week_index' => 'required|integer|min:1',
            'weeks.*.shift_id' => 'required|exists:shifts,id',
        ]);

        DB::transaction(function () use ($validated, $shift_rotation) {
            $shift_rotation->update([
                'employee_id' => $validated['employee_id'] ?? null,
                'cycle_length_weeks' => $validated['cycle_length_weeks'],
                'effective_date' => $validated['effective_date'] ?? null,
                'expiry_date' => $validated['expiry_date'] ?? null,
            ]);

            ShiftRotationWeek::where('rotation_id', $shift_rotation->id)->delete();
            foreach ($validated['weeks'] as $week) {
                ShiftRotationWeek::create([
                    'rotation_id' => $shift_rotation->id,
                    'week_index' => $week['week_index'],
                    'shift_id' => $week['shift_id'],
                ]);
            }
        });

        return redirect()->route('shift-rotations.index')->with('success', 'Rotation updated');
    }

    public function destroy(ShiftRotation $shift_rotation)
    {
        $shift_rotation->delete();
        return redirect()->route('shift-rotations.index')->with('success', 'Rotation deleted');
    }
}


