<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoLeitoController;
use App\Http\Controllers\LeitoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\InternacaoController;

Route::apiResource('tipos-leito', TipoLeitoController::class);
Route::apiResource('leitos', LeitoController::class);
Route::apiResource('pacientes', PacienteController::class);
Route::apiResource('internacoes', InternacaoController::class);

Route::patch('internacoes/{internacao}/alta', [InternacaoController::class, 'alta']);
Route::patch('internacoes/{internacao}/transferir', [InternacaoController::class, 'transferir']);

Route::get('pacientes/cpf/{cpf}/leito', [PacienteController::class, 'buscarLeitoPorCpf']);

Route::get('leitos/{leito}/status', [LeitoController::class, 'status']);
Route::get('leitos-status', [LeitoController::class, 'listarStatus']);
