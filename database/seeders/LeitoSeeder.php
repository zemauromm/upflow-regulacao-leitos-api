<?php

namespace Database\Seeders;

use App\Models\Leito;
use App\Models\TipoLeito;
use Illuminate\Database\Seeder;

class LeitoSeeder extends Seeder
{
    public function run(): void
    {
        $uti = TipoLeito::where(
            'descricao',
            'UTI'
        )->first();

        $enfermaria = TipoLeito::where(
            'descricao',
            'Enfermaria'
        )->first();

        Leito::create([
            'numero' => 'UTI-01',
            'tipo_leito_id' => $uti->id,
            'ativo' => true
        ]);

        Leito::create([
            'numero' => 'UTI-02',
            'tipo_leito_id' => $uti->id,
            'ativo' => true
        ]);

        Leito::create([
            'numero' => 'ENF-01',
            'tipo_leito_id' => $enfermaria->id,
            'ativo' => true
        ]);
    }
}
