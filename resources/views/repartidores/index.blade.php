@extends('layouts.app')

@section('title', 'Repartidores')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Repartidores</h1>
    <a href="{{ route('repartidores.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm hover:bg-emerald-700">Nuevo Repartidor</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vehículo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($repartidores as $repartidor)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $repartidor->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $repartidor->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($repartidor->vehicle_type) }} {{ $repartidor->license_place ?? '' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $repartidor->status === 'available' ? 'bg-green-100 text-green-800' : ($repartidor->status === 'busy' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $repartidor->status === 'available' ? 'Disponible' : ($repartidor->status === 'busy' ? 'Ocupado' : 'Inactivo') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm space-x-2">
                        <a href="{{ route('repartidores.show', $repartidor) }}" class="text-emerald-600 hover:text-emerald-900">Ver</a>
                        <a href="{{ route('repartidores.edit', $repartidor) }}" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                        <form action="{{ route('repartidores.destroy', $repartidor) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este repartidor?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">No hay repartidores registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t">
        {{ $repartidores->links() }}
    </div>
</div>
@endsection
