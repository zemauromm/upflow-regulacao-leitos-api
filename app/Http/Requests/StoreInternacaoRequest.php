<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInternacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paciente_id' => 'required|exists:pacientes,id',
            'leito_id' => 'required|exists:leitos,id',
            'data_internacao' => 'required|date',
            'status' => 'nullable|string|max:20'
        ];
    }
}
