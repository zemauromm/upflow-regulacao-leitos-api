<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInternacaoRequest;
use App\Http\Requests\TransferirInternacaoRequest;
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
        summary: 'Lista todas as internacoes',
        tags: ['Internacoes'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de internacoes retornada com sucesso'
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
        summary: 'Registra uma nova internacao',
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
            new OA\Response(response: 201, description: 'Internacao registrada com sucesso'),
            new OA\Response(response: 422, description: 'Regra de negócio ou validacao não atendida')
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

    #[OA\Get(
        path: '/api/internacoes/{internacao}',
        summary: 'Exibe uma internacao específica',
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
            new OA\Response(response: 200, description: 'Internacao retornada com sucesso'),
            new OA\Response(response: 404, description: 'Internacao não encontrada')
        ]
    )]
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

    #[OA\Put(
        path: '/api/internacoes/{internacao}',
        summary: 'Atualiza uma internacao',
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
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'paciente_id', type: 'integer', example: 1),
                    new OA\Property(property: 'leito_id', type: 'integer', example: 2),
                    new OA\Property(property: 'data_internacao', type: 'string', example: '2026-06-17 22:00:00'),
                    new OA\Property(property: 'data_alta', type: 'string', nullable: true, example: null),
                    new OA\Property(property: 'status', type: 'string', example: 'INTERNADO')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Internacao atualizada com sucesso'),
            new OA\Response(response: 422, description: 'Erro de validacao')
        ]
    )]
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
        summary: 'Registra alta de uma internacao',
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
            new OA\Response(response: 422, description: 'Internacao já possui alta registrada')
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

    #[OA\Patch(
        path: '/api/internacoes/{internacao}/transferir',
        summary: 'Transfere um paciente para outro leito',
        description: 'Transfere uma internacao ativa para outro leito disponível.',
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
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['leito_id'],
                properties: [
                    new OA\Property(
                        property: 'leito_id',
                        type: 'integer',
                        example: 2
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paciente transferido com sucesso'
            ),
            new OA\Response(
                response: 422,
                description: 'Regra de negócio ou validacao não atendida'
            )
        ]
    )]
    public function transferir(
        TransferirInternacaoRequest $request,
        Internacao $internacao
    ) {
        $dados = $request->validated();

        try {
            $internacao = $this
                ->regulacaoLeitosService
                ->transferirPaciente(
                    $internacao,
                    $dados['leito_id']
                );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }

        return response()->json($internacao);
    }

    #[OA\Delete(
        path: '/api/internacoes/{internacao}',
        summary: 'Remove uma internacao',
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
            new OA\Response(response: 204, description: 'Internacao removida com sucesso'),
            new OA\Response(response: 404, description: 'Internacao não encontrada')
        ]
    )]
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
