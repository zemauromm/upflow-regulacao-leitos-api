<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeitoRequest;
use App\Http\Requests\UpdateLeitoRequest;
use App\Models\Leito;
use App\Services\RegulacaoLeitosService;
use OpenApi\Attributes as OA;

class LeitoController extends Controller
{
    private RegulacaoLeitosService $regulacaoLeitosService;

    public function __construct(
        RegulacaoLeitosService $regulacaoLeitosService
    ) {
        $this->regulacaoLeitosService = $regulacaoLeitosService;
    }

    #[OA\Get(
        path: '/api/leitos',
        summary: 'Lista todos os leitos',
        tags: ['Leitos'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de leitos retornada com sucesso'
            )
        ]
    )]
    public function index()
    {
        return response()->json(
            Leito::with(
                'tipoLeito'
            )->get()
        );
    }

    #[OA\Post(
        path: '/api/leitos',
        summary: 'Cadastra um novo leito',
        tags: ['Leitos'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['numero', 'tipo_leito_id', 'ativo'],
                properties: [
                    new OA\Property(
                        property: 'numero',
                        type: 'string',
                        example: 'UTI-03'
                    ),
                    new OA\Property(
                        property: 'tipo_leito_id',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'ativo',
                        type: 'boolean',
                        example: true
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Leito cadastrado com sucesso'
            ),
            new OA\Response(
                response: 422,
                description: 'Erro de validação'
            )
        ]
    )]
    public function store(
        StoreLeitoRequest $request
    ) {
        $dados = $request->validated();

        $leito = Leito::create(
            $dados
        );

        return response()->json(
            $leito->load(
                'tipoLeito'
            ),
            201
        );
    }

    #[OA\Get(
        path: '/api/leitos/{leito}',
        summary: 'Exibe um leito específico',
        tags: ['Leitos'],
        parameters: [
            new OA\Parameter(
                name: 'leito',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Leito retornado com sucesso'),
            new OA\Response(response: 404, description: 'Leito não encontrado')
        ]
    )]
    public function show(
        Leito $leito
    ) {
        return response()->json(
            $leito->load(
                'tipoLeito'
            )
        );
    }

    #[OA\Put(
        path: '/api/leitos/{leito}',
        summary: 'Atualiza um leito',
        tags: ['Leitos'],
        parameters: [
            new OA\Parameter(
                name: 'leito',
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
                    new OA\Property(
                        property: 'numero',
                        type: 'string',
                        example: 'UTI-03'
                    ),
                    new OA\Property(
                        property: 'tipo_leito_id',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'ativo',
                        type: 'boolean',
                        example: true
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Leito atualizado com sucesso'),
            new OA\Response(response: 422, description: 'Erro de validação')
        ]
    )]
    public function update(
        UpdateLeitoRequest $request,
        Leito $leito
    ) {
        $dados = $request->validated();

        $leito->update(
            $dados
        );

        return response()->json(
            $leito->load(
                'tipoLeito'
            )
        );
    }

    #[OA\Get(
        path: '/api/leitos/{leito}/status',
        summary: 'Verifica o status de ocupação de um leito',
        description: 'Retorna se o leito está LIVRE ou OCUPADO. Caso esteja ocupado, retorna o paciente atual.',
        tags: ['Leitos'],
        parameters: [
            new OA\Parameter(
                name: 'leito',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Status do leito retornado com sucesso'
            ),
            new OA\Response(
                response: 404,
                description: 'Leito não encontrado'
            )
        ]
    )]
    public function status(
        Leito $leito
    ) {
        return response()->json(
            $this
                ->regulacaoLeitosService
                ->verificarStatusLeito($leito)
        );
    }

    #[OA\Get(
        path: '/api/leitos-status',
        summary: 'Lista todos os leitos com status de ocupação',
        description: 'Retorna todos os leitos com tipo, status LIVRE ou OCUPADO e paciente atual quando houver.',
        tags: ['Leitos'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de leitos com status retornada com sucesso'
            )
        ]
    )]
    public function listarStatus()
    {
        return response()->json(
            $this
                ->regulacaoLeitosService
                ->listarLeitosComStatus()
        );
    }

    #[OA\Delete(
        path: '/api/leitos/{leito}',
        summary: 'Remove um leito',
        tags: ['Leitos'],
        parameters: [
            new OA\Parameter(
                name: 'leito',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            )
        ],
        responses: [
            new OA\Response(response: 204, description: 'Leito removido com sucesso'),
            new OA\Response(response: 404, description: 'Leito não encontrado')
        ]
    )]
    public function destroy(
        Leito $leito
    ) {
        $leito->delete();

        return response()->json(
            null,
            204
        );
    }
}
