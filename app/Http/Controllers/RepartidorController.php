<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRepartidorRequest;
use App\Http\Requests\UpdateRepartidorRequest;
use App\Models\Repartidor;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RepartidorController extends Controller
{
    public function index(): View
    {
        $repartidores = Repartidor::latest()->paginate(10);

        return view('repartidores.index', compact('repartidores'));
    }

    public function create(): View
    {
        return view('repartidores.create');
    }

    public function store(StoreRepartidorRequest $request): RedirectResponse
    {
        Repartidor::create($request->validated());

        return redirect()->route('repartidores.index')->with('success', 'Repartidor creado correctamente.');
    }

    public function show(Repartidor $repartidor): View
    {
        return view('repartidores.show', compact('repartidor'));
    }

    public function edit(Repartidor $repartidor): View
    {
        return view('repartidores.edit', compact('repartidor'));
    }

    public function update(UpdateRepartidorRequest $request, Repartidor $repartidor): RedirectResponse
    {
        $repartidor->update($request->validated());

        return redirect()->route('repartidores.index')->with('success', 'Repartidor actualizado correctamente.');
    }

    public function destroy(Repartidor $repartidor): RedirectResponse
    {
        $repartidor->delete();

        return redirect()->route('repartidores.index')->with('success', 'Repartidor eliminado correctamente.');
    }
}
