@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Editar Usuario</h1>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name', $usuario->name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña <span class="text-gray-400 font-normal">(dejar vacío para mantener)</span></label>
            <input type="password" name="password" id="password" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $usuario->phone) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                <select name="role" id="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="cliente" {{ old('role', $usuario->role) === 'cliente' ? 'selected' : '' }}>Cliente</option>
                    <option value="admin" {{ old('role', $usuario->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="repartidor" {{ old('role', $usuario->role) === 'repartidor' ? 'selected' : '' }}>Repartidor</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="active" {{ old('status', $usuario->status) === 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="inactive" {{ old('status', $usuario->status) === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('usuarios.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm hover:bg-emerald-700">Actualizar</button>
        </div>
    </form>
</div>
@endsection
