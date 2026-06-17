<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $paciente = $this->route('paciente');

        return [
            'nome' =>
            'required|string|max:150',

            'data_nascimento' =>
            'required|date',

            'cpf' =>
            'required|string|size:11|unique:pacientes,cpf,' .
                $paciente->id,

            'cartao_sus' =>
            'nullable|string|max:20'
        ];
    }
}
