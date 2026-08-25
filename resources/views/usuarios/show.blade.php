@extends('layouts.app')

@section('title', $usuario->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">{{ $usuario->name }}</h1>
    <div class="space-x-2">
        <a href="{{ route('usuarios.edit', $usuario) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm hover:bg-yellow-600">Editar</a>
        <a href="{{ route('usuarios.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Volver</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <dl class="space-y-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">Nombre</dt>
            <dd class="text-sm text-gray-900">{{ $usuario->name }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Email</dt>
            <dd class="text-sm text-gray-900">{{ $usuario->email }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
            <dd class="text-sm text-gray-900">{{ $usuario->phone ?: 'Sin registro' }}</dd>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Rol</dt>
                <dd><span class="px-2 py-1 text-xs rounded-full {{ $usuario->role === 'admin' ? 'bg-purple-100 text-purple-800' : ($usuario->role === 'repartidor' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst($usuario->role) }}</span></dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                <dd><span class="px-2 py-1 text-xs rounded-full {{ $usuario->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $usuario->status === 'active' ? 'Activo' : 'Inactivo' }}</span></dd>
            </div>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Miembro desde</dt>
            <dd class="text-sm text-gray-900">{{ $usuario->created_at->format('d/m/Y') }}</dd>
        </div>
    </dl>
</div>
@endsection
