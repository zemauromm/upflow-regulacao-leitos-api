<?php

namespace Database\Seeders;

use App\Models\Paciente;
use Illuminate\Database\Seeder;

class PacienteSeeder extends Seeder
{
    public function run(): void
    {
        Paciente::create([
            'nome' => 'Joao da Silva',
            'data_nascimento' => '1980-05-10',
            'cpf' => '12345678901',
            'cartao_sus' => '123456789012345'
        ]);

        Paciente::create([
            'nome' => 'Maria Souza',
            'data_nascimento' => '1990-08-15',
            'cpf' => '98765432100',
            'cartao_sus' => '987654321098765'
        ]);

        Paciente::create([
            'nome' => 'Carlos Pereira',
            'data_nascimento' => '1975-11-22',
            'cpf' => '11122233344',
            'cartao_sus' => '111222333444555'
        ]);
    }
}
