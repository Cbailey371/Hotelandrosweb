<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = \App\Models\User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];

        // Solo super_admin puede asignar roles. Otros (si llegaran aquí) crearían receptores por defecto.
        if (auth()->user()->role === 'super_admin') {
            $rules['role'] = 'required|string|in:' . implode(',', array_keys(\App\Models\User::ROLES));
        }

        $validated = $request->validate($rules);
        $role = $validated['role'] ?? 'reception';

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => $role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ];

        // Solo super_admin puede cambiar roles
        $canChangeRole = auth()->user()->role === 'super_admin';

        // No permitir que el usuario se cambie su propio rol si es super_admin
        // (Para evitar quedarse sin administradores o auto-degradarse por error)
        if ($id == auth()->id() && $user->role === 'super_admin') {
            $canChangeRole = false;
        }

        if ($canChangeRole) {
            $rules['role'] = 'required|string|in:' . implode(',', array_keys(\App\Models\User::ROLES));
        }

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (isset($validated['role'])) {
            $user->role = $validated['role'];
        }

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (auth()->id() == $id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
