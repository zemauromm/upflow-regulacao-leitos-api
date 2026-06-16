<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('internacoes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')
                ->constrained('pacientes');

            $table->foreignId('leito_id')
                ->constrained('leitos');

            $table->dateTime('data_internacao');

            $table->dateTime('data_alta')
                ->nullable();

            $table->string('status', 20)
                ->default('INTERNADO');

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internacoes');
    }
};
