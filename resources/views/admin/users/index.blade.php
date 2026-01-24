@extends('layouts.admin')

@section('header', 'Gestionar Usuarios')

@section('content')
    <div class="flex flex-col gap-8">
        <!-- Actions Toolbar -->
        <div class="flex justify-between items-center">
            <div class="flex flex-col">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">Lista de Usuarios</h2>
                <p class="text-sm text-slate-500">Administra los accesos y roles del sistema.</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
                class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-sm">add_circle</span>
                Nuevo Usuario
            </a>
        </div>

        @if(session('success'))
            <div
                class="p-4 bg-green-100 text-green-700 rounded-xl border border-green-200 text-sm font-bold flex items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-red-100 text-red-700 rounded-xl border border-red-200 text-sm font-bold flex items-center gap-2">
                <span class="material-symbols-outlined">error</span>
                {{ session('error') }}
            </div>
        @endif

        <!-- Users Table -->
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800 text-xs uppercase tracking-wider text-slate-500 font-bold">
                            <th class="px-6 py-4">Nombre</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Rol</th>
                            <th class="px-6 py-4">Registro</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($users as $user)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <span class="font-bold text-slate-800 dark:text-white text-sm">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $roleColors = [
                                            'super_admin' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'supervisor' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'reception' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        ];
                                        $color = $roleColors[$user->role] ?? 'bg-slate-100 text-slate-700';
                                        $roleName = \App\Models\User::ROLES[$user->role] ?? $user->role;
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $color }}">
                                        {{ $roleName }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-all">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all">
                                                    <span class="material-symbols-outlined text-lg">delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection