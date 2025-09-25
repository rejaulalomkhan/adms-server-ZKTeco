<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Area;
use App\Models\Office;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HolidayController extends Controller
{
    public function index()
    {
        $data['lable'] = 'Holidays';
        return view('holidays.index', $data);
    }

    public function data(Request $request)
    {
        $query = Holiday::query()->with(['area','office']);
        return DataTables::of($query)
            ->addColumn('area_name', function (Holiday $h) { return optional($h->area)->name; })
            ->addColumn('office_name', function (Holiday $h) { return optional($h->office)->name; })
            ->addColumn('action', function (Holiday $h) {
                $editUrl = route('holidays.edit', $h->id);
                $deleteUrl = route('holidays.destroy', $h->id);
                return view('holidays.partials.actions', compact('editUrl', 'deleteUrl', 'h'))->render();
            })
            ->make(true);
    }

    public function create()
    {
        $areas = Area::orderBy('name')->get(['id','name']);
        $offices = Office::orderBy('name')->get(['id','name']);
        return view('holidays.create', compact('areas','offices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'is_recurring' => 'nullable|boolean',
            'area_id' => 'nullable|exists:areas,id',
            'office_id' => 'nullable|exists:offices,id',
        ]);
        $validated['is_recurring'] = (bool) $request->boolean('is_recurring');
        Holiday::create($validated);
        return redirect()->route('holidays.index')->with('success', 'Holiday created');
    }

    public function edit(Holiday $holiday)
    {
        $areas = Area::orderBy('name')->get(['id','name']);
        $offices = Office::orderBy('name')->get(['id','name']);
        return view('holidays.edit', ['h' => $holiday, 'areas' => $areas, 'offices' => $offices]);
    }

    public function update(Request $request, Holiday $holiday)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'is_recurring' => 'nullable|boolean',
            'area_id' => 'nullable|exists:areas,id',
            'office_id' => 'nullable|exists:offices,id',
        ]);
        $validated['is_recurring'] = (bool) $request->boolean('is_recurring');
        $holiday->update($validated);
        return redirect()->route('holidays.index')->with('success', 'Holiday updated');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday deleted');
    }
}


