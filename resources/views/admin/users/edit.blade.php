@extends('layouts.admin')

@section('header', 'Editar Usuario')

@section('content')
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary mb-6 transition-colors">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Volver a la lista
        </a>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center gap-4 mb-8">
                <div
                    class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-lg">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 dark:text-white">Editar Usuario</h2>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                </div>
            </div>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-primary/50 outline-none transition-all">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-primary/50 outline-none transition-all">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Rol</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach(\App\Models\User::ROLES as $key => $label)
                            <label class="cursor-pointer relative">
                                <input type="radio" name="role" value="{{ $key }}" class="peer sr-only" {{ old('role', $user->role) == $key ? 'checked' : '' }}>
                                <div
                                    class="p-4 rounded-xl border-2 border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 hover:bg-white dark:hover:bg-slate-900 peer-checked:border-primary peer-checked:bg-primary/5 transition-all text-center">
                                    <span
                                        class="block text-sm font-bold text-slate-700 dark:text-slate-300 peer-checked:text-primary">{{ $label }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800 pt-6 mt-6">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">Cambiar Contraseña (Opcional)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nueva
                                Contraseña</label>
                            <input type="password" name="password"
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-primary/50 outline-none transition-all">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Confirmar
                                Contraseña</label>
                            <input type="password" name="password_confirmation"
                                class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-primary/50 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit"
                        class="bg-primary text-white px-8 py-3 rounded-xl font-black text-sm shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all uppercase tracking-wider">
                        Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection