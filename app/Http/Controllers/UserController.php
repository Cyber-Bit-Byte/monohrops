<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdminOrManager();

        $users = User::orderBy('name')->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorizeAdminOrManager();

        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdminOrManager();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,employee,developer,administrative,team-lead,client,visitor',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        $this->authorizeAdminOrManager();

        $user->load('employee');

        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $this->authorizeAdminOrManager();

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeAdminOrManager();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,manager,employee,developer,administrative,team-lead,client,visitor',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorizeAdminOrManager();

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}