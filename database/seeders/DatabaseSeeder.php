<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TipoLeitoSeeder::class,
            LeitoSeeder::class,
            PacienteSeeder::class
        ]);
    }
}
