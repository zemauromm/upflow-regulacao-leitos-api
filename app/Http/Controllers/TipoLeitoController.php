<?php

namespace App\Http\Controllers;

use App\Models\TipoLeito;
use Illuminate\Http\Request;

class TipoLeitoController extends Controller
{
    public function index()
    {
        return response()->json(TipoLeito::all());
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'descricao' => 'required|string|max:50|unique:tipos_leito,descricao'
        ]);

        $tipoLeito = TipoLeito::create($dados);

        return response()->json($tipoLeito, 201);
    }

    public function show(TipoLeito $tipos_leito)
    {
        return response()->json($tipos_leito);
    }

    public function update(Request $request, TipoLeito $tipos_leito)
    {
        $dados = $request->validate([
            'descricao' => 'required|string|max:50|unique:tipos_leito,descricao,' . $tipos_leito->id
        ]);

        $tipos_leito->update($dados);

        return response()->json($tipos_leito);
    }

    public function destroy(TipoLeito $tipos_leito)
    {
        $tipos_leito->delete();

        return response()->json(null, 204);
    }
}
