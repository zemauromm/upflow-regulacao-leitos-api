<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeitoRequest;
use App\Http\Requests\UpdateLeitoRequest;
use App\Models\Leito;
use OpenApi\Attributes as OA;

class LeitoController extends Controller
{
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

    public function show(
        Leito $leito
    ) {
        return response()->json(
            $leito->load(
                'tipoLeito'
            )
        );
    }

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
