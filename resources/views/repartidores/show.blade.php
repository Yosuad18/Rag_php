@extends('layouts.app')

@section('title', $repartidor->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">{{ $repartidor->name }}</h1>
    <div class="space-x-2">
        <a href="{{ route('repartidores.edit', $repartidor) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm hover:bg-yellow-600">Editar</a>
        <a href="{{ route('repartidores.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Volver</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <dl class="space-y-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">Nombre</dt>
            <dd class="text-sm text-gray-900">{{ $repartidor->name }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Email</dt>
            <dd class="text-sm text-gray-900">{{ $repartidor->email }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
            <dd class="text-sm text-gray-900">{{ $repartidor->phone ?: 'Sin registro' }}</dd>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Vehículo</dt>
                <dd class="text-sm text-gray-900">{{ ucfirst($repartidor->vehicle_type) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Matrícula</dt>
                <dd class="text-sm text-gray-900">{{ $repartidor->license_plate ?: 'N/A' }}</dd>
            </div>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Estado</dt>
            <dd>
                <span class="px-2 py-1 text-xs rounded-full {{ $repartidor->status === 'available' ? 'bg-green-100 text-green-800' : ($repartidor->status === 'busy' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                    {{ $repartidor->status === 'available' ? 'Disponible' : ($repartidor->status === 'busy' ? 'Ocupado' : 'Inactivo') }}
                </span>
            </dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Registrado</dt>
            <dd class="text-sm text-gray-900">{{ $repartidor->created_at->format('d/m/Y') }}</dd>
        </div>
    </dl>
</div>
@endsection
