<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

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
        $roles = Role::orderBy('name')->get(['id','name']);
        $permissions = Permission::orderBy('name')->get(['id','name']);
        return view('users.create', compact('offices','roles','permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'office_id' => 'nullable|exists:offices,id',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'office_id' => $validated['office_id'] ?? null,
        ]);

        if (!empty($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }
        // Handle profile image upload into per-employee folder under storage/app/public/employees/{slug}/
        if ($request->hasFile('profile_image')) {
            $employeeSlug = Str::slug($user->name ?: ('user-'.$user->id));
            $path = $request->file('profile_image')->store("employees/{$employeeSlug}", 'public');
            $user->profile_image = $path;
            $user->save();
        }
        // No direct permissions here

        return redirect()->route('users.index')->with('success', 'Employee created');
    }

    public function edit(User $user)
    {
        $offices = Office::orderBy('name')->get(['id','name']);
        $roles = Role::orderBy('name')->get(['id','name']);
        $permissions = Permission::orderBy('name')->get(['id','name']);
        $userRoleNames = $user->roles()->pluck('name')->toArray();
        $userPermissionNames = $user->permissions()->pluck('name')->toArray();
        return view('users.edit', compact('user','offices','roles','permissions','userRoleNames','userPermissionNames'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6',
            'office_id' => 'nullable|exists:offices,id',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->office_id = $validated['office_id'] ?? null;
        $user->save();

        // Update roles/permissions
        $user->syncRoles($validated['roles'] ?? []);
        // Update profile image
        if ($request->hasFile('profile_image')) {
            $employeeSlug = Str::slug($user->name ?: ('user-'.$user->id));
            $path = $request->file('profile_image')->store("employees/{$employeeSlug}", 'public');
            $user->profile_image = $path;
            $user->save();
        }
        // No direct permissions here

        return redirect()->route('users.index')->with('success', 'Employee updated');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Employee deleted');
    }
}



