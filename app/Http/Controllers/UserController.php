<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        // If Manager, filter out Admin role
        if (!auth()->user()->hasRole('Admin')) {
            $roles = $roles->where('name', '!=', 'Admin');
        }
        return view('users.form', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
        ]);

        if (!auth()->user()->hasRole('Admin') && $validated['role'] === 'Admin') {
            return back()->with('error', 'You cannot create an Admin user.');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        // Manager cannot edit Admin
        if (!auth()->user()->hasRole('Admin') && $user->hasRole('Admin')) {
            return back()->with('error', 'You cannot edit an Admin user.');
        }

        $roles = Role::all();
        if (!auth()->user()->hasRole('Admin')) {
            $roles = $roles->where('name', '!=', 'Admin');
        }

        return view('users.form', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->hasRole('Admin') && $user->hasRole('Admin')) {
            return back()->with('error', 'You cannot edit an Admin user.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'exists:roles,name'],
        ]);

        if (!auth()->user()->hasRole('Admin') && $validated['role'] === 'Admin') {
            return back()->with('error', 'You cannot assign the Admin role.');
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->hasRole('Admin') && $user->hasRole('Admin')) {
            return back()->with('error', 'You cannot delete an Admin user.');
        }
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
