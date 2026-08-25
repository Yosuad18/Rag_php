@extends('layouts.app')

@section('title', $producto->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">{{ $producto->name }}</h1>
    <div class="space-x-2">
        <a href="{{ route('productos.edit', $producto) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm hover:bg-yellow-600">Editar</a>
        <a href="{{ route('productos.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Volver</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <dl class="space-y-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">Nombre</dt>
            <dd class="text-sm text-gray-900">{{ $producto->name }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Descripción</dt>
            <dd class="text-sm text-gray-900">{{ $producto->description ?: 'Sin descripción' }}</dd>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Precio</dt>
                <dd class="text-sm text-gray-900">${{ number_format($producto->price, 2) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Stock</dt>
                <dd class="text-sm text-gray-900">{{ $producto->stock }}</dd>
            </div>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Estado</dt>
            <dd>
                <span class="px-2 py-1 text-xs rounded-full {{ $producto->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $producto->status === 'active' ? 'Activo' : 'Inactivo' }}
                </span>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Creado</dt>
            <dd class="text-sm text-gray-900">{{ $producto->created_at->format('d/m/Y H:i') }}</dd>
        </div>
    </dl>
</div>
@endsection
