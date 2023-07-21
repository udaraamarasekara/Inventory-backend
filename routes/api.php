<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GoodsController;

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

Route::middleware('auth:sanctum')->group( function () {
});
Route::apiResource('admin',AdminController::class);
Route::apiResource('good',GoodsController::class);
Route::post('goodDetail/{type}',[GoodsController::class,'addGoodDetail']);
Route::get('goodDetails/{type}',[GoodsController::class,'viewGoodDetails']);
Route::delete('goodDetail/{type}/{id}',[GoodsController::class,'deleteGoodDetail']);
Route::put('goodDetail/{type}/{id}',[GoodsController::class,'updateGoodDetail']);
Route::post('sales',[GoodsController::class,'allSales']);
Route::post('grns',[GoodsController::class,'allGrns']);
Route::post('profitLost',[GoodsController::class,'profitLost']);
Route::post('allTimeSales',[GoodsController::class,'allTimeSales']);
Route::post('allTimeGrns',[GoodsController::class,'allTimeGrns']);
