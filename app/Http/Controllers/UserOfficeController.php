<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Office;
use Yajra\DataTables\Facades\DataTables;

class UserOfficeController extends Controller
{
    public function index()
    {
        return view('user_offices.index');
    }

    public function data()
    {
        $q = User::query()->leftJoin('offices','offices.id','=','users.office_id')
            ->select('users.id','users.name','users.email','offices.name as office_name');
        return DataTables::of($q)
            ->addColumn('action', function($row){
                $editUrl = route('user-offices.edit', $row->id);
                return view('user_offices.partials.actions', compact('editUrl','row'))->render();
            })
            ->make(true);
    }

    public function edit(User $user)
    {
        $offices = Office::orderBy('name')->get(['id','name']);
        return view('user_offices.edit', compact('user','offices'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'office_id' => 'nullable|exists:offices,id',
        ]);
        $user->office_id = $validated['office_id'] ?? null;
        $user->save();
        return redirect()->route('user-offices.index')->with('success', 'User office updated');
    }
}


