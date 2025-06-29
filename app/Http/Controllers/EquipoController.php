<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index()
    {
        $equipos = Equipo::with('tipoEquipo')->get();
        return response()->json($equipos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'modelo' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'tipo_equipo_id' => 'required|exists:tipo_equipos,id',
            'fecha_fabricacion' => 'nullable|date',
            'potencia_kw' => 'required|numeric',
            'velocidad_rpm' => 'required|integer',
            'proveedor' => 'nullable|string|max:255',
            'informacion_adicional' => 'nullable|string',
            'vigencia_anos' => 'nullable|integer',
            'requiere_calibracion' => 'boolean'
        ]);

        $equipo = Equipo::create($validated);
        return response()->json($equipo, 201);
    }

    public function show(Equipo $equipo)
    {
        return response()->json($equipo->load('tipoEquipo'));
    }

    public function update(Request $request, Equipo $equipo)
    {
        $validated = $request->validate([
            'modelo' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'tipo_equipo_id' => 'required|exists:tipo_equipos,id',
            'fecha_fabricacion' => 'nullable|date',
            'potencia_kw' => 'required|numeric',
            'velocidad_rpm' => 'required|integer',
            'proveedor' => 'nullable|string|max:255',
            'informacion_adicional' => 'nullable|string',
            'vigencia_anos' => 'nullable|integer',
            'requiere_calibracion' => 'boolean'
        ]);

        $equipo->update($validated);
        return response()->json($equipo);
    }

    public function destroy(Equipo $equipo)
    {
        $equipo->delete();
        return response()->json(null, 204);
    }
}