<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoLeito extends Model
{
    protected $table = 'tipos_leito';

    protected $fillable = [
        'descricao'
    ];

    public function leitos(): HasMany
    {
        return $this->hasMany(Leito::class);
    }
}
