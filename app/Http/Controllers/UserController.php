<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::leftJoin('offices', 'offices.id', '=', 'users.office_id')
            ->select('users.*', 'offices.name as office_name')
            ->orderBy('users.id', 'desc')
            ->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $offices = Office::orderBy('name')->get(['id','name']);
        return view('users.create', compact('offices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'office_id' => 'nullable|exists:offices,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'office_id' => $validated['office_id'] ?? null,
        ]);

        return redirect()->route('users.index')->with('success', 'Employee created');
    }

    public function edit(User $user)
    {
        $offices = Office::orderBy('name')->get(['id','name']);
        return view('users.edit', compact('user','offices'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6',
            'office_id' => 'nullable|exists:offices,id',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->office_id = $validated['office_id'] ?? null;
        $user->save();

        return redirect()->route('users.index')->with('success', 'Employee updated');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Employee deleted');
    }
}



