<?php

namespace App\Http\Controllers;

use App\Models\Internacao;
use Illuminate\Http\Request;

class InternacaoController extends Controller
{
    public function index()
    {
        // Carrega paciente, leito e tipo de leito para
        // retornar uma visao completa das internacoes.
        return response()->json(
            Internacao::with(['paciente', 'leito.tipoLeito'])->get()
        );
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'leito_id' => 'required|exists:leitos,id',
            'data_internacao' => 'required|date',
            'status' => 'nullable|string|max:20'
        ]);

        // Regra de negocio:
        // um paciente nao pode possuir mais de uma internacao ativa.
        $internacaoAtivaPaciente = Internacao::where(
            'paciente_id',
            $dados['paciente_id']
        )
            ->whereNull('data_alta')
            ->exists();

        if ($internacaoAtivaPaciente) {
            return response()->json([
                'message' => 'Paciente ja possui internacao ativa.'
            ], 422);
        }

        // Regra de negocio:
        // um leito nao pode estar ocupado por mais de um
        // paciente simultaneamente.
        $leitoOcupado = Internacao::where(
            'leito_id',
            $dados['leito_id']
        )
            ->whereNull('data_alta')
            ->exists();

        if ($leitoOcupado) {
            return response()->json([
                'message' => 'Leito ja esta ocupado.'
            ], 422);
        }

        $dados['status'] = $dados['status'] ?? 'INTERNADO';

        $internacao = Internacao::create($dados);

        return response()->json(
            $internacao->load(['paciente', 'leito.tipoLeito']),
            201
        );
    }

    public function show(Internacao $internacao)
    {
        // Retorna a internacao juntamente com o paciente,
        // leito e tipo de leito.
        return response()->json(
            $internacao->load(['paciente', 'leito.tipoLeito'])
        );
    }

    public function update(Request $request, Internacao $internacao)
    {
        $dados = $request->validate([
            'data_alta' => 'nullable|date',
            'status' => 'nullable|string|max:20'
        ]);

        // Permite registrar alta e alteracao de status
        // de uma internacao existente.
        $internacao->update($dados);

        return response()->json(
            $internacao->load(['paciente', 'leito.tipoLeito'])
        );
    }

    public function alta(Internacao $internacao)
    {
        if ($internacao->data_alta !== null) {
            return response()->json([
                'message' => 'Internacao ja possui alta registrada.'
            ], 422);
        }

        $internacao->update([
            'data_alta' => now(),
            'status' => 'ALTA'
        ]);

        return response()->json(
            $internacao->load(['paciente', 'leito.tipoLeito'])
        );
    }

    public function destroy(Internacao $internacao)
    {
        $internacao->delete();

        return response()->json(null, 204);
    }
}
