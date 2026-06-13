<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::with('department')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $departments = \App\Models\Department::where('status', 'active')->get();
        return view('users.form', ['user' => new \App\Models\User(), 'departments' => $departments]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'user_code' => 'nullable|string|max:255|unique:users,user_code',
            'phone_number' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'role' => 'required|in:admin,super_user,normal_user',
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'required|in:active,inactive',
            'password' => 'nullable|string|min:3'
        ]);

        if(!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            $validated['password'] = bcrypt('password'); // Default password if empty
        }

        \App\Models\User::create($validated);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(\App\Models\User $user)
    {
        $departments = \App\Models\Department::where('status', 'active')->get();
        return view('users.form', compact('user', 'departments'));
    }

    public function update(Request $request, \App\Models\User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'user_code' => 'nullable|string|max:255|unique:users,user_code,' . $user->id,
            'phone_number' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'role' => 'required|in:admin,super_user,normal_user',
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'required|in:active,inactive',
            'is_approved' => 'boolean',
            'password' => 'nullable|string|min:3'
        ]);

        if(!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function show(\App\Models\User $user)
    {
        return view('users.show', compact('user'));
    }

    public function destroy(\App\Models\User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleApproval(\App\Models\User $user)
    {
        $user->update([
            'is_approved' => !$user->is_approved,
        ]);
        return redirect()->back()->with('success', 'User approval status updated.');
    }
}