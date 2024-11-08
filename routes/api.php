<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvestimentoController;
use App\Http\Controllers\TransacaoController;

// Rotas de autenticação
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protegendo rotas com Sanctum (para usuários autenticados)
Route::middleware('auth:sanctum')->group(function () {

    // Rotas de logout
    Route::post('logout', [AuthController::class, 'logout']);

    // Rotas para investimentos
    Route::apiResource('investimentos', InvestimentoController::class);

    // Rotas para transações
    Route::apiResource('transacoes', TransacaoController::class);
});

// Retornar informações do usuário autenticado

Route::middleware('auth:sanctum')->get('/usuario-autenticado', [AuthController::class, 'usuarioAutenticado']);

Route::middleware('auth:sanctum')->get('/users', [AuthController::class, 'index']);


