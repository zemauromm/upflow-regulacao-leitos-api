<?php

namespace App\Http\Controllers;

use App\Models\Leito;
use Illuminate\Http\Request;

class LeitoController extends Controller
{
    public function index()
    {
        return response()->json(
            Leito::with('tipoLeito')->get()
        );
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'numero' => 'required|string|max:20|unique:leitos,numero',
            'tipo_leito_id' => 'required|exists:tipos_leito,id',
            'ativo' => 'required|boolean'
        ]);

        $leito = Leito::create($dados);

        return response()->json($leito, 201);
    }

    public function show(Leito $leito)
    {
        return response()->json(
            $leito->load('tipoLeito')
        );
    }

    public function update(Request $request, Leito $leito)
    {
        $dados = $request->validate([
            'numero' => 'required|string|max:20|unique:leitos,numero,' . $leito->id,
            'tipo_leito_id' => 'required|exists:tipos_leito,id',
            'ativo' => 'required|boolean'
        ]);

        $leito->update($dados);

        return response()->json($leito);
    }

    public function destroy(Leito $leito)
    {
        $leito->delete();

        return response()->json(null, 204);
    }
}
