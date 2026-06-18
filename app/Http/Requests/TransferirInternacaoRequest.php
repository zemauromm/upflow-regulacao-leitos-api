<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferirInternacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'leito_id' => [
                'required',
                'integer',
                'exists:leitos,id'
            ]
        ];
    }
}
