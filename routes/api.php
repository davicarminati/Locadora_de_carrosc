<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CarrosController;
use App\Http\Controllers\LocacaoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;

Route::get('/api', function () {
    return response()->json([
        'mensagem' => 'API funcionando'
    ]);
});

Route::apiResource('cliente', ClienteController::class);
Route::apiResource('carros', CarrosController::class);
Route::apiResource('locacao', LocacaoController::class);
Route::apiResource('marca', MarcaController::class);
Route::apiResource('modelo', ModeloController::class);