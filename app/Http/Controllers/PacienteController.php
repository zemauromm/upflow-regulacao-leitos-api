<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index()
    {
        return response()->json(Paciente::all());
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:150',
            'data_nascimento' => 'required|date',
            'cpf' => 'required|string|size:11|unique:pacientes,cpf',
            'cartao_sus' => 'nullable|string|max:20'
        ]);

        $paciente = Paciente::create($dados);

        return response()->json($paciente, 201);
    }

    public function show(Paciente $paciente)
    {
        return response()->json($paciente);
    }

    public function update(Request $request, Paciente $paciente)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:150',
            'data_nascimento' => 'required|date',
            'cpf' => 'required|string|size:11|unique:pacientes,cpf,' . $paciente->id,
            'cartao_sus' => 'nullable|string|max:20'
        ]);

        $paciente->update($dados);

        return response()->json($paciente);
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->delete();

        return response()->json(null, 204);
    }
}
