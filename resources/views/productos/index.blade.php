@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Productos</h1>
    <a href="{{ route('productos.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm hover:bg-emerald-700">Nuevo Producto</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($productos as $producto)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $producto->name }}</div>
                        @if ($producto->description)
                            <div class="text-sm text-gray-500">{{ Str::limit($producto->description, 60) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">${{ number_format($producto->price, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $producto->stock }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $producto->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $producto->status === 'active' ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm space-x-2">
                        <a href="{{ route('productos.show', $producto) }}" class="text-emerald-600 hover:text-emerald-900">Ver</a>
                        <a href="{{ route('productos.edit', $producto) }}" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este producto?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">No hay productos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t">
        {{ $productos->links() }}
    </div>
</div>
@endsection
