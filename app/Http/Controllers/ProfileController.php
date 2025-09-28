<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403);
        }
        /** @var User $user */
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        if ($request->hasFile('profile_image')) {
            $employeeSlug = \Illuminate\Support\Str::slug($user->name ?: ('user-'.$user->id));
            $path = $request->file('profile_image')->store("employees/{$employeeSlug}", 'public');
            $user->profile_image = $path;
            $user->save();
        }

        return redirect()->route('profile.edit')->with('success', 'Profile updated');
    }
}


