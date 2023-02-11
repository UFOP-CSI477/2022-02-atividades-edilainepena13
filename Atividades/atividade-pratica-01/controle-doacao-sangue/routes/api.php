<?php

use App\Http\Controllers\TiposSanguineosController;
use App\Http\Controllers\CidadesController;
use App\Http\Controllers\DoacoesController;
use App\Http\Controllers\EstadosController;
use App\Http\Controllers\LocaisColetaController;
use App\Http\Controllers\PessoaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('tipossanguineos', TiposSanguineosController::class);
Route::apiResource('cidades', CidadesController::class);
Route::get('/cidades/pornome/{nome}', [CidadesController::class , 'showPorNome']);
Route::apiResource('estados', EstadosController::class);
Route::get('/estados/pornome/{nome}', [EstadosController::class , 'showPorNome']);
Route::apiResource('locaiscoleta', LocaisColetaController::class);
Route::get('/locaiscoleta/pornome/{nome}', [LocaisColetaController::class , 'showPorNome']);
Route::apiResource('pessoas', PessoaController::class);
Route::get('/pessoas/pornome/{nome}', [PessoaController::class , 'showPorNome']);
Route::apiResource('doacoes', DoacoesController::class);
