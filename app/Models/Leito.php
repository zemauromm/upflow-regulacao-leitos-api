<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Leito extends Model
{
    protected $table = 'leitos';

    protected $fillable = [
        'numero',
        'tipo_leito_id',
        'ativo'
    ];

    public function tipoLeito(): BelongsTo
    {
        return $this->belongsTo(TipoLeito::class);
    }

    public function internacoes(): HasMany
    {
        return $this->hasMany(Internacao::class);
    }
}
