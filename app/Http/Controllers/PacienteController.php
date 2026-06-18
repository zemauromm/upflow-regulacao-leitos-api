<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use App\Models\Paciente;
use App\Services\RegulacaoLeitosService;
use OpenApi\Attributes as OA;

class PacienteController extends Controller
{
    private RegulacaoLeitosService $regulacaoLeitosService;

    public function __construct(
        RegulacaoLeitosService $regulacaoLeitosService
    ) {
        $this->regulacaoLeitosService = $regulacaoLeitosService;
    }

    #[OA\Get(
        path: '/api/pacientes',
        summary: 'Lista todos os pacientes',
        tags: ['Pacientes'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de pacientes retornada com sucesso'
            )
        ]
    )]
    public function index()
    {
        return response()->json(
            Paciente::all()
        );
    }

    #[OA\Post(
        path: '/api/pacientes',
        summary: 'Cadastra um novo paciente',
        tags: ['Pacientes'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['nome', 'data_nascimento', 'cpf'],
                properties: [
                    new OA\Property(property: 'nome', type: 'string', example: 'Ana Oliveira'),
                    new OA\Property(property: 'data_nascimento', type: 'string', format: 'date', example: '1995-03-20'),
                    new OA\Property(property: 'cpf', type: 'string', example: '22233344455'),
                    new OA\Property(property: 'cartao_sus', type: 'string', example: '222333444555666')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Paciente cadastrado com sucesso'),
            new OA\Response(response: 422, description: 'Erro de validação')
        ]
    )]
    public function store(
        StorePacienteRequest $request
    ) {
        $dados = $request->validated();

        $paciente = Paciente::create(
            $dados
        );

        return response()->json(
            $paciente,
            201
        );
    }

    #[OA\Get(
        path: '/api/pacientes/{paciente}',
        summary: 'Exibe um paciente específico',
        tags: ['Pacientes'],
        parameters: [
            new OA\Parameter(
                name: 'paciente',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Paciente retornado com sucesso'),
            new OA\Response(response: 404, description: 'Paciente não encontrado')
        ]
    )]
    public function show(
        Paciente $paciente
    ) {
        return response()->json(
            $paciente
        );
    }

    #[OA\Put(
        path: '/api/pacientes/{paciente}',
        summary: 'Atualiza um paciente',
        tags: ['Pacientes'],
        parameters: [
            new OA\Parameter(
                name: 'paciente',
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
                    new OA\Property(property: 'nome', type: 'string', example: 'Ana Oliveira'),
                    new OA\Property(property: 'data_nascimento', type: 'string', format: 'date', example: '1995-03-20'),
                    new OA\Property(property: 'cpf', type: 'string', example: '22233344455'),
                    new OA\Property(property: 'cartao_sus', type: 'string', example: '222333444555666')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Paciente atualizado com sucesso'),
            new OA\Response(response: 422, description: 'Erro de validação')
        ]
    )]
    public function update(
        UpdatePacienteRequest $request,
        Paciente $paciente
    ) {
        $dados = $request->validated();

        $paciente->update(
            $dados
        );

        return response()->json(
            $paciente
        );
    }

    #[OA\Get(
        path: '/api/pacientes/cpf/{cpf}/leito',
        summary: 'Busca o leito atual de um paciente pelo CPF',
        description: 'Retorna o paciente, a internação ativa, o leito ocupado e o tipo de leito.',
        tags: ['Pacientes'],
        parameters: [
            new OA\Parameter(
                name: 'cpf',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string'),
                example: '11122233344'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Leito do paciente retornado com sucesso'
            ),
            new OA\Response(
                response: 422,
                description: 'Paciente não encontrado ou sem internação ativa'
            )
        ]
    )]
    public function buscarLeitoPorCpf(
        string $cpf
    ) {
        try {
            $resultado = $this
                ->regulacaoLeitosService
                ->buscarLeitoPorCpf($cpf);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }

        return response()->json($resultado);
    }

    #[OA\Delete(
        path: '/api/pacientes/{paciente}',
        summary: 'Remove um paciente',
        tags: ['Pacientes'],
        parameters: [
            new OA\Parameter(
                name: 'paciente',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            )
        ],
        responses: [
            new OA\Response(response: 204, description: 'Paciente removido com sucesso'),
            new OA\Response(response: 404, description: 'Paciente não encontrado')
        ]
    )]
    public function destroy(
        Paciente $paciente
    ) {
        $paciente->delete();

        return response()->json(
            null,
            204
        );
    }
}
