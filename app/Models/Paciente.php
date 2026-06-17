<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    protected $table = 'pacientes';

    protected $fillable = [
        'nome',
        'data_nascimento',
        'cpf',
        'cartao_sus'
    ];

    public function internacoes(): HasMany
    {
        return $this->hasMany(Internacao::class);
    }
}
