<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use App\Models\Paciente;
use OpenApi\Attributes as OA;

class PacienteController extends Controller
{
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

    public function show(
        Paciente $paciente
    ) {
        return response()->json(
            $paciente
        );
    }

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
