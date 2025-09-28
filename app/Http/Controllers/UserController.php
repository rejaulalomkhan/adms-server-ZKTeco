<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ShiftAssignment;
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
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
            'profile_image' => 'nullable|image|max:2048',
            'documents.*' => 'file|max:4096',
            'documents_types.*' => 'nullable|string|max:100',
            'fingerprint_id' => 'nullable|string|max:64|unique:users,fingerprint_id',
            'shift_id' => 'nullable|exists:shifts,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'office_id' => $validated['office_id'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'department' => $validated['department'] ?? null,
            'join_date' => $validated['join_date'] ?? null,
            'fingerprint_id' => $validated['fingerprint_id'] ?? null,
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

        // Store additional documents
        if ($request->hasFile('documents')) {
            $employeeSlug = Str::slug($user->name ?: ('user-'.$user->id));
            foreach ($request->file('documents') as $idx => $doc) {
                $stored = $doc->store("employees/{$employeeSlug}/documents", 'public');
                $user->documents()->create([
                    'type' => $request->input('documents_types.'.$idx),
                    'path' => $stored,
                    'original_name' => $doc->getClientOriginalName(),
                ]);
            }
        }

        // Assign shift if provided
        if (!empty($validated['shift_id'])) {
            ShiftAssignment::create([
                'employee_id' => $user->id,
                'shift_id' => $validated['shift_id'],
                'start_date' => now()->toDateString(),
                'priority' => 1,
                'reason' => 'Assigned on create',
            ]);
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
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'join_date' => 'nullable|date',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
            'profile_image' => 'nullable|image|max:2048',
            'documents.*' => 'file|max:4096',
            'documents_types.*' => 'nullable|string|max:100',
            'fingerprint_id' => 'nullable|string|max:64|unique:users,fingerprint_id,'.$user->id,
            'shift_id' => 'nullable|exists:shifts,id',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->office_id = $validated['office_id'] ?? null;
        $user->designation = $validated['designation'] ?? null;
        $user->department = $validated['department'] ?? null;
        $user->join_date = $validated['join_date'] ?? null;
        $user->fingerprint_id = $validated['fingerprint_id'] ?? null;
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
        if ($request->hasFile('documents')) {
            $employeeSlug = Str::slug($user->name ?: ('user-'.$user->id));
            foreach ($request->file('documents') as $idx => $doc) {
                $stored = $doc->store("employees/{$employeeSlug}/documents", 'public');
                $user->documents()->create([
                    'type' => $request->input('documents_types.'.$idx),
                    'path' => $stored,
                    'original_name' => $doc->getClientOriginalName(),
                ]);
            }
        }

        if (!empty($validated['shift_id'])) {
            ShiftAssignment::create([
                'employee_id' => $user->id,
                'shift_id' => $validated['shift_id'],
                'start_date' => now()->toDateString(),
                'priority' => 1,
                'reason' => 'Assigned on update',
            ]);
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



