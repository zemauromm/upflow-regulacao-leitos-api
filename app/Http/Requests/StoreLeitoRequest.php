<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeitoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero' =>
            'required|string|max:20|unique:leitos,numero',

            'tipo_leito_id' =>
            'required|exists:tipos_leito,id',

            'ativo' =>
            'required|boolean'
        ];
    }
}
