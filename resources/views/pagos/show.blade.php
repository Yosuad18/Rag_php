@extends('layouts.app')

@section('title', 'Pago #' . $pago->id)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Pago #{{ $pago->id }}</h1>
    <div class="space-x-2">
        <a href="{{ route('pagos.edit', $pago) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md text-sm hover:bg-yellow-600">Editar</a>
        <a href="{{ route('pagos.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Volver</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <dl class="space-y-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">Usuario</dt>
            <dd class="text-sm text-gray-900">{{ $pago->user->name }} ({{ $pago->user->email }})</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Monto</dt>
            <dd class="text-sm text-gray-900">${{ number_format($pago->amount, 2) }}</dd>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Método</dt>
                <dd class="text-sm text-gray-900">{{ ucfirst($pago->payment_method) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                <dd>
                    <span class="px-2 py-1 text-xs rounded-full {{ $pago->status === 'completed' ? 'bg-green-100 text-green-800' : ($pago->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($pago->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                        {{ $pago->status === 'completed' ? 'Completado' : ($pago->status === 'pending' ? 'Pendiente' : ($pago->status === 'failed' ? 'Fallido' : 'Reembolsado')) }}
                    </span>
                </dd>
            </div>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Descripción</dt>
            <dd class="text-sm text-gray-900">{{ $pago->description ?: 'Sin descripción' }}</dd>
        </div>
        @if ($pago->paid_at)
            <div>
                <dt class="text-sm font-medium text-gray-500">Pagado el</dt>
                <dd class="text-sm text-gray-900">{{ $pago->paid_at->format('d/m/Y H:i') }}</dd>
            </div>
        @endif
        <div>
            <dt class="text-sm font-medium text-gray-500">Registrado</dt>
            <dd class="text-sm text-gray-900">{{ $pago->created_at->format('d/m/Y H:i') }}</dd>
        </div>
    </dl>
</div>
@endsection
