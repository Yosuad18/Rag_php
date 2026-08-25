@extends('layouts.app')

@section('title', 'Nuevo Pago')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Nuevo Pago</h1>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <form action="{{ route('pagos.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
            <select name="user_id" id="user_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
                <option value="">Seleccionar usuario...</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>{{ $usuario->name }} ({{ $usuario->email }})</option>
                @endforeach
            </select>
            @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Monto</label>
            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
            @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Método de pago</label>
                <select name="payment_method" id="payment_method" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Efectivo</option>
                    <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Tarjeta</option>
                    <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transferencia</option>
                </select>
                @error('payment_method') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="failed" {{ old('status') === 'failed' ? 'selected' : '' }}>Fallido</option>
                    <option value="refunded" {{ old('status') === 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
            <textarea name="description" id="description" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="paid_at" class="block text-sm font-medium text-gray-700 mb-1">Fecha de pago</label>
            <input type="datetime-local" name="paid_at" id="paid_at" value="{{ old('paid_at') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
            @error('paid_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('pagos.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm hover:bg-emerald-700">Guardar</button>
        </div>
    </form>
</div>
@endsection
