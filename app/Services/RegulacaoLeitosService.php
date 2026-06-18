<?php

namespace App\Services;

use App\Models\Internacao;
use App\Models\Leito;
use App\Models\Paciente;
use Exception;

/**
 * Serviço responsável pelas regras de negócio do processo de
 * regulação de leitos hospitalares.
 *
 * Centraliza validações relacionadas a ocupação de leitos,
 * internações ativas, alta hospitalar, transferência de pacientes
 * e consultas de status/localização.
 */
class RegulacaoLeitosService
{
    public function buscarLeito(int $leitoId): ?Leito
    {
        return Leito::find($leitoId);
    }

    public function leitoAtivo(int $leitoId): bool
    {
        $leito = $this->buscarLeito($leitoId);

        return $leito && $leito->ativo;
    }

    public function leitoOcupado(int $leitoId): bool
    {
        return Internacao::where('leito_id', $leitoId)
            ->whereNull('data_alta')
            ->exists();
    }

    public function pacienteInternado(int $pacienteId): bool
    {
        return Internacao::where('paciente_id', $pacienteId)
            ->whereNull('data_alta')
            ->exists();
    }

    /**
     * Valida as regras para uma nova internação.
     *
     * Regras:
     * - paciente não pode possuir internação ativa;
     * - leito deve existir;
     * - leito deve estar ativo;
     * - leito não pode estar ocupado.
     *
     * @throws Exception
     */
    public function validarInternacao(
        int $pacienteId,
        int $leitoId
    ): void {
        if ($this->pacienteInternado($pacienteId)) {
            throw new Exception('Paciente ja possui internacao ativa.');
        }

        $leito = $this->buscarLeito($leitoId);

        if (!$leito) {
            throw new Exception('Leito nao encontrado.');
        }

        if (!$leito->ativo) {
            throw new Exception('Leito esta inativo.');
        }

        if ($this->leitoOcupado($leitoId)) {
            throw new Exception('Leito ja esta ocupado.');
        }
    }

    /**
     * Registra alta de uma internação ativa.
     *
     * @throws Exception
     */
    public function registrarAlta(
        Internacao $internacao
    ): Internacao {
        if ($internacao->data_alta !== null) {
            throw new Exception('Internacao ja possui alta registrada.');
        }

        $internacao->update([
            'data_alta' => now(),
            'status' => 'ALTA'
        ]);

        return $internacao->fresh([
            'paciente',
            'leito.tipoLeito'
        ]);
    }

    /**
     * Transfere uma internação ativa para outro leito disponível.
     *
     * @throws Exception
     */
    public function transferirPaciente(
        Internacao $internacao,
        int $novoLeitoId
    ): array {
        if ($internacao->data_alta !== null) {
            throw new Exception('Nao e possivel transferir uma internacao finalizada.');
        }

        $novoLeito = $this->buscarLeito($novoLeitoId);

        if (!$novoLeito) {
            throw new Exception('Leito nao encontrado.');
        }

        if (!$novoLeito->ativo) {
            throw new Exception('Leito esta inativo.');
        }

        if ((int) $internacao->leito_id === $novoLeitoId) {
            throw new Exception('Paciente ja esta neste leito.');
        }

        if ($this->leitoOcupado($novoLeitoId)) {
            throw new Exception('Leito ja esta ocupado.');
        }

        $internacao->update([
            'leito_id' => $novoLeitoId
        ]);

        $internacao = $internacao->fresh([
            'paciente',
            'leito.tipoLeito'
        ]);

        return [
            'message' => 'Paciente transferido com sucesso.',
            'internacao' => [
                'id' => $internacao->id,
                'status' => $internacao->status,
                'data_internacao' => $internacao->data_internacao,
                'data_alta' => $internacao->data_alta
            ],
            'paciente' => [
                'id' => $internacao->paciente->id,
                'nome' => $internacao->paciente->nome,
                'cpf' => $internacao->paciente->cpf
            ],
            'leito_atual' => [
                'id' => $internacao->leito->id,
                'numero' => $internacao->leito->numero,
                'tipo' => $internacao->leito->tipoLeito->descricao
            ]
        ];
    }

    /**
     * Localiza o leito ocupado por um paciente a partir do CPF.
     *
     * @throws Exception
     */
    public function buscarLeitoPorCpf(
        string $cpf
    ): array {
        $paciente = Paciente::where('cpf', $cpf)->first();

        if (!$paciente) {
            throw new Exception('Paciente nao encontrado.');
        }

        $internacao = Internacao::with([
            'paciente',
            'leito.tipoLeito'
        ])
            ->where('paciente_id', $paciente->id)
            ->whereNull('data_alta')
            ->first();

        if (!$internacao) {
            throw new Exception('Paciente nao possui internacao ativa.');
        }

        return [
            'paciente' => [
                'id' => $internacao->paciente->id,
                'nome' => $internacao->paciente->nome,
                'cpf' => $internacao->paciente->cpf
            ],
            'internacao' => [
                'id' => $internacao->id,
                'status' => $internacao->status,
                'data_internacao' => $internacao->data_internacao
            ],
            'leito' => [
                'id' => $internacao->leito->id,
                'numero' => $internacao->leito->numero,
                'tipo' => $internacao->leito->tipoLeito->descricao
            ]
        ];
    }

    /**
     * Retorna o status atual de ocupação de um leito.
     */
    public function verificarStatusLeito(
        Leito $leito
    ): array {
        $leito->load('tipoLeito');

        $internacao = Internacao::with('paciente')
            ->where('leito_id', $leito->id)
            ->whereNull('data_alta')
            ->first();

        return [
            'id' => $leito->id,
            'numero' => $leito->numero,
            'tipo' => $leito->tipoLeito->descricao,
            'status' => $internacao ? 'OCUPADO' : 'LIVRE',
            'paciente_atual' => $internacao
                ? [
                    'id' => $internacao->paciente->id,
                    'nome' => $internacao->paciente->nome,
                    'cpf' => $internacao->paciente->cpf
                ]
                : null
        ];
    }

    /**
     * Lista todos os leitos com seus respectivos status.
     */
    public function listarLeitosComStatus()
    {
        return Leito::with('tipoLeito')
            ->get()
            ->map(function (Leito $leito) {
                return $this->verificarStatusLeito($leito);
            });
    }
}
