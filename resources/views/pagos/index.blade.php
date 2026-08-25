@extends('layouts.app')

@section('title', 'Pagos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Pagos</h1>
    <a href="{{ route('pagos.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm hover:bg-emerald-700">Nuevo Pago</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Método</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($pagos as $pago)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $pago->user->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">${{ number_format($pago->amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($pago->payment_method) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $pago->status === 'completed' ? 'bg-green-100 text-green-800' : ($pago->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($pago->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ $pago->status === 'completed' ? 'Completado' : ($pago->status === 'pending' ? 'Pendiente' : ($pago->status === 'failed' ? 'Fallido' : 'Reembolsado')) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $pago->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-right text-sm space-x-2">
                        <a href="{{ route('pagos.show', $pago) }}" class="text-emerald-600 hover:text-emerald-900">Ver</a>
                        <a href="{{ route('pagos.edit', $pago) }}" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                        <form action="{{ route('pagos.destroy', $pago) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este pago?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">No hay pagos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t">
        {{ $pagos->links() }}
    </div>
</div>
@endsection
