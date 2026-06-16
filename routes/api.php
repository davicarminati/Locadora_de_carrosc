<?php

use Illuminate\Support\Facades\Route;

Route::get('/api', function () {
    return response()->json([
        'mensagem' => 'API funcionando'
    ]);
});

Route::apiResource('cliente', 'ClienteController');
Route::apiResource('carros', 'CarrosController');
Route::apiResource('locacao', 'LocacaoController');
Route::apiResource('marca', 'MarcaController');
Route::apiResource('modelo', 'ModeloController');