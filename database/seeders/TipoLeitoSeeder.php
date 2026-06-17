<?php

namespace Database\Seeders;

use App\Models\TipoLeito;
use Illuminate\Database\Seeder;

class TipoLeitoSeeder extends Seeder
{
    public function run(): void
    {
        TipoLeito::create([
            'descricao' => 'UTI'
        ]);

        TipoLeito::create([
            'descricao' => 'Enfermaria'
        ]);

        TipoLeito::create([
            'descricao' => 'Observacao'
        ]);
    }
}
