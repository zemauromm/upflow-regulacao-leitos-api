<?php

namespace App\Services;

use App\Models\Internacao;
use App\Models\Leito;
use Exception;

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
        return Internacao::where(
            'leito_id',
            $leitoId
        )
            ->whereNull('data_alta')
            ->exists();
    }

    public function pacienteInternado(int $pacienteId): bool
    {
        return Internacao::where(
            'paciente_id',
            $pacienteId
        )
            ->whereNull('data_alta')
            ->exists();
    }

    /**
     * Valida as regras de negócio de uma nova internação.
     */
    public function validarInternacao(
        int $pacienteId,
        int $leitoId
    ): void {
        if ($this->pacienteInternado($pacienteId)) {
            throw new Exception(
                'Paciente ja possui internacao ativa.'
            );
        }

        $leito = $this->buscarLeito($leitoId);

        if (!$leito) {
            throw new Exception(
                'Leito nao encontrado.'
            );
        }

        if (!$leito->ativo) {
            throw new Exception(
                'Leito esta inativo.'
            );
        }

        if ($this->leitoOcupado($leitoId)) {
            throw new Exception(
                'Leito ja esta ocupado.'
            );
        }
    }

    /**
     * Registra a alta da internação.
     */
    public function registrarAlta(
        Internacao $internacao
    ): Internacao {
        if ($internacao->data_alta !== null) {
            throw new Exception(
                'Internacao ja possui alta registrada.'
            );
        }

        $internacao->update([
            'data_alta' => now(),
            'status' => 'ALTA'
        ]);

        return $internacao->fresh();
    }
}
