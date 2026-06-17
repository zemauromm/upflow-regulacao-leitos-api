<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeitoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $leito = $this->route('leito');

        return [
            'numero' =>
            'required|string|max:20|unique:leitos,numero,' .
                $leito->id,

            'tipo_leito_id' =>
            'required|exists:tipos_leito,id',

            'ativo' =>
            'required|boolean'
        ];
    }
}
