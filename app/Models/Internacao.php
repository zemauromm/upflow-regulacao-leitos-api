<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Internacao extends Model
{
    protected $table = 'internacoes';

    protected $fillable = [
        'paciente_id',
        'leito_id',
        'data_internacao',
        'data_alta',
        'status'
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function leito(): BelongsTo
    {
        return $this->belongsTo(Leito::class);
    }
}
