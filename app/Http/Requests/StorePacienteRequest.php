<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' =>
            'required|string|max:150',

            'data_nascimento' =>
            'required|date',

            'cpf' =>
            'required|string|size:11|unique:pacientes,cpf',

            'cartao_sus' =>
            'nullable|string|max:20'
        ];
    }
}
