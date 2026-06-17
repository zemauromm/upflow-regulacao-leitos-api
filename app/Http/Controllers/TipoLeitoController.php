<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoLeitoRequest;
use App\Http\Requests\UpdateTipoLeitoRequest;
use App\Models\TipoLeito;
use OpenApi\Attributes as OA;

class TipoLeitoController extends Controller
{
    #[OA\Get(
        path: '/api/tipos-leito',
        summary: 'Lista todos os tipos de leito',
        tags: ['Tipos de Leito'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista retornada com sucesso'
            )
        ]
    )]

    public function index()
    {
        return response()->json(
            TipoLeito::all()
        );
    }

    #[OA\Post(
        path: '/api/tipos-leito',
        summary: 'Cadastra um novo tipo de leito',
        tags: ['Tipos de Leito'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['descricao'],
                properties: [
                    new OA\Property(
                        property: 'descricao',
                        type: 'string',
                        example: 'Semi-UTI'
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Tipo de leito cadastrado com sucesso'
            ),
            new OA\Response(
                response: 422,
                description: 'Erro de validação'
            )
        ]
    )]

    public function store(
        StoreTipoLeitoRequest $request
    ) {
        $dados = $request->validated();

        $tipoLeito = TipoLeito::create(
            $dados
        );

        return response()->json(
            $tipoLeito,
            201
        );
    }

    public function show(
        TipoLeito $tipos_leito
    ) {
        return response()->json(
            $tipos_leito
        );
    }

    public function update(
        UpdateTipoLeitoRequest $request,
        TipoLeito $tipos_leito
    ) {
        $dados = $request->validated();

        $tipos_leito->update(
            $dados
        );

        return response()->json(
            $tipos_leito
        );
    }

    public function destroy(
        TipoLeito $tipos_leito
    ) {
        $tipos_leito->delete();

        return response()->json(
            null,
            204
        );
    }
}
