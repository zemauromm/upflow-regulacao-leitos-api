<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInternacaoRequest;
use App\Http\Requests\UpdateInternacaoRequest;
use App\Models\Internacao;
use App\Services\RegulacaoLeitosService;
use OpenApi\Attributes as OA;

class InternacaoController extends Controller
{
    private RegulacaoLeitosService $regulacaoLeitosService;

    public function __construct(
        RegulacaoLeitosService $regulacaoLeitosService
    ) {
        $this->regulacaoLeitosService = $regulacaoLeitosService;
    }

    #[OA\Get(
        path: '/api/internacoes',
        summary: 'Lista todas as internações',
        tags: ['Internacoes'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de internações retornada com sucesso'
            )
        ]
    )]

    public function index()
    {
        return response()->json(
            Internacao::with([
                'paciente',
                'leito.tipoLeito'
            ])->get()
        );
    }

    #[OA\Post(
        path: '/api/internacoes',
        summary: 'Registra uma nova internação',
        tags: ['Internacoes'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['paciente_id', 'leito_id', 'data_internacao'],
                properties: [
                    new OA\Property(property: 'paciente_id', type: 'integer', example: 1),
                    new OA\Property(property: 'leito_id', type: 'integer', example: 1),
                    new OA\Property(property: 'data_internacao', type: 'string', example: '2026-06-17 22:00:00')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Internação registrada com sucesso'),
            new OA\Response(response: 422, description: 'Regra de negócio ou validação não atendida')
        ]
    )]

    public function store(
        StoreInternacaoRequest $request
    ) {
        $dados = $request->validated();

        try {
            $this->regulacaoLeitosService
                ->validarInternacao(
                    $dados['paciente_id'],
                    $dados['leito_id']
                );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }

        $dados['status'] =
            $dados['status'] ?? 'INTERNADO';

        $internacao = Internacao::create($dados);

        return response()->json(
            $internacao->load([
                'paciente',
                'leito.tipoLeito'
            ]),
            201
        );
    }

    public function show(
        Internacao $internacao
    ) {
        return response()->json(
            $internacao->load([
                'paciente',
                'leito.tipoLeito'
            ])
        );
    }

    public function update(
        UpdateInternacaoRequest $request,
        Internacao $internacao
    ) {
        $dados = $request->validated();

        $internacao->update($dados);

        return response()->json(
            $internacao->load([
                'paciente',
                'leito.tipoLeito'
            ])
        );
    }

    #[OA\Patch(
        path: '/api/internacoes/{internacao}/alta',
        summary: 'Registra alta de uma internação',
        tags: ['Internacoes'],
        parameters: [
            new OA\Parameter(
                name: 'internacao',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Alta registrada com sucesso'),
            new OA\Response(response: 422, description: 'Internação já possui alta registrada')
        ]
    )]

    public function alta(
        Internacao $internacao
    ) {
        try {
            $internacao = $this
                ->regulacaoLeitosService
                ->registrarAlta($internacao);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }

        return response()->json(
            $internacao->load([
                'paciente',
                'leito.tipoLeito'
            ])
        );
    }

    public function destroy(
        Internacao $internacao
    ) {
        $internacao->delete();

        return response()->json(
            null,
            204
        );
    }
}
