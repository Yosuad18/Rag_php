@extends('layouts.app')

@section('title', 'Editar Repartidor')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Editar Repartidor</h1>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
   <form action="{{ route('repartidores.update', ['repartidore' => $repartidor->getKey()]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name', $repartidor->name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $repartidor->email) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" required>
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $repartidor->phone) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">Vehículo</label>
                <select name="vehicle_type" id="vehicle_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                    <option value="moto" {{ old('vehicle_type', $repartidor->vehicle_type) === 'moto' ? 'selected' : '' }}>Moto</option>
                    <option value="coche" {{ old('vehicle_type', $repartidor->vehicle_type) === 'coche' ? 'selected' : '' }}>Coche</option>
                    <option value="bici" {{ old('vehicle_type', $repartidor->vehicle_type) === 'bici' ? 'selected' : '' }}>Bicicleta</option>
                    <option value="pie" {{ old('vehicle_type', $repartidor->vehicle_type) === 'pie' ? 'selected' : '' }}>A pie</option>
                </select>
                @error('vehicle_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-1">Matrícula</label>
                <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate', $repartidor->license_plate) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                @error('license_plate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
            <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                <option value="available" {{ old('status', $repartidor->status) === 'available' ? 'selected' : '' }}>Disponible</option>
                <option value="busy" {{ old('status', $repartidor->status) === 'busy' ? 'selected' : '' }}>Ocupado</option>
                <option value="inactive" {{ old('status', $repartidor->status) === 'inactive' ? 'selected' : '' }}>Inactivo</option>
            </select>
            @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('repartidores.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm hover:bg-emerald-700">Actualizar</button>
        </div>
    </form>
</div>
@endsection
