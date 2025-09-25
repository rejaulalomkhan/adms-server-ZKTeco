<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Area;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OfficeController extends Controller
{
    public function index()
    {
        return view('offices.index');
    }

    public function data()
    {
        $q = Office::with('area');
        return DataTables::of($q)
            ->addColumn('area_name', function(Office $o){ return optional($o->area)->name; })
            ->addColumn('action', function(Office $o){
                $editUrl = route('offices.edit', $o->id);
                $deleteUrl = route('offices.destroy', $o->id);
                return view('offices.partials.actions', compact('editUrl','deleteUrl','o'))->render();
            })
            ->make(true);
    }

    public function create()
    {
        $areas = Area::orderBy('name')->get(['id','name']);
        return view('offices.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'area_id' => 'nullable|exists:areas,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);
        Office::create($validated);
        return redirect()->route('offices.index')->with('success', 'Office created');
    }

    public function edit(Office $office)
    {
        $areas = Area::orderBy('name')->get(['id','name']);
        return view('offices.edit', ['office' => $office, 'areas' => $areas]);
    }

    public function update(Request $request, Office $office)
    {
        $validated = $request->validate([
            'area_id' => 'nullable|exists:areas,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
        ]);
        $office->update($validated);
        return redirect()->route('offices.index')->with('success', 'Office updated');
    }

    public function destroy(Office $office)
    {
        $office->delete();
        return redirect()->route('offices.index')->with('success', 'Office deleted');
    }
}


