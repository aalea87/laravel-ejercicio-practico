<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('register',[AuthController::class, 'register'])->middleware('guest');
Route::post('login',[AuthController::class, 'login'])->middleware('guest');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('logout',[AuthController::class, 'logout']);

    Route::controller(ProductoController::class)->group(function(){
        Route::get('productos', 'get');
        Route::get('productos/cantidad', 'count');
        Route::get('productos/{id}', 'show');
        Route::post('productos', 'create');
        Route::put('productos/{id}', 'update');
        Route::patch('productos/{id}', 'updatePatch');
        Route::delete('productos/{id}','destroy');
    });

    Route::controller(OrdenController::class)->group(function(){
        Route::get('carrito', 'show');
        Route::post('carrito', 'create');
        Route::put('carrito', 'sell');
        Route::delete('carrito/{producto_id}','destroy');

        Route::get('vendidos','vendidos');
        Route::get('ganancias','ganancias');
        Route::get('vacio','reporteVacios');
    });
});

