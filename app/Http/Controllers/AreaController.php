<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AreaController extends Controller
{
    public function index()
    {
        return view('areas.index');
    }

    public function data()
    {
        $q = Area::with('parent');
        return DataTables::of($q)
            ->addColumn('parent_name', function(Area $a){ return optional($a->parent)->name; })
            ->addColumn('action', function(Area $a){
                $editUrl = route('areas.edit', $a->id);
                $deleteUrl = route('areas.destroy', $a->id);
                return view('areas.partials.actions', compact('editUrl','deleteUrl','a'))->render();
            })
            ->make(true);
    }

    public function create()
    {
        $areas = Area::orderBy('name')->get(['id','name']);
        return view('areas.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:areas,id',
        ]);
        Area::create($validated);
        return redirect()->route('areas.index')->with('success', 'Area created');
    }

    public function edit(Area $area)
    {
        $areas = Area::where('id', '!=', $area->id)->orderBy('name')->get(['id','name']);
        return view('areas.edit', compact('area','areas'));
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:areas,id',
        ]);
        $area->update($validated);
        return redirect()->route('areas.index')->with('success', 'Area updated');
    }

    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('areas.index')->with('success', 'Area deleted');
    }
}


