<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTipoLeitoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipoLeito = $this->route('tipos_leito');

        return [
            'descricao' =>
            'required|string|max:50|unique:tipos_leito,descricao,' .
                $tipoLeito->id
        ];
    }
}
