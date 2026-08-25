<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePagoRequest;
use App\Http\Requests\UpdatePagoRequest;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function index(): View
    {
        $pagos = Pago::with('user')->latest()->paginate(10);

        return view('pagos.index', compact('pagos'));
    }

    public function create(): View
    {
        $usuarios = User::where('status', 'active')->get();

        return view('pagos.create', compact('usuarios'));
    }

    public function store(StorePagoRequest $request): RedirectResponse
    {
        Pago::create($request->validated());

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function show(Pago $pago): View
    {
        $pago->load('user');

        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago): View
    {
        $usuarios = User::where('status', 'active')->get();

        return view('pagos.edit', compact('pago', 'usuarios'));
    }

    public function update(UpdatePagoRequest $request, Pago $pago): RedirectResponse
    {
        $pago->update($request->validated());

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(Pago $pago): RedirectResponse
    {
        $pago->delete();

        return redirect()->route('pagos.index')->with('success', 'Pago eliminado correctamente.');
    }
}
