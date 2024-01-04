<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BuiltInTasksController;

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
    Route::apiResource('good',GoodsController::class);
    Route::apiResource('admin',AdminController::class);
    Route::get('goodsCount',[GoodsController::class,'goodsCount']);
    Route::apiResource('builtInTask',BuiltInTasksController::class);
    Route::post('goodDetail/{type}',[GoodsController::class,'addGoodDetail']);
    Route::get('goodDetails/{type}',[GoodsController::class,'viewGoodDetails']);
    Route::delete('goodDetail/{type}/{id}',[GoodsController::class,'deleteGoodDetail']);
    Route::put('goodDetail/{type}/{id}',[GoodsController::class,'updateGoodDetail']);
    Route::post('sales',[GoodsController::class,'allSales']);
    Route::post('grns',[GoodsController::class,'allGrns']);
    Route::post('profitLost',[GoodsController::class,'profitLost']);
    Route::post('allTimeSales',[GoodsController::class,'allTimeSales']);
    Route::post('allTimeGrns',[GoodsController::class,'allTimeGrns']);
    Route::post('allGoodDetailSales',[GoodsController::class,'allGoodDetailSales']);
    Route::post('allGoodDetailGrns',[GoodsController::class,'allGoodDetailGrns']);
    Route::post('allTimeGoodDetailSales',[GoodsController::class,'allTimeGoodDetailSales']);
    Route::post('allTimeGoodDetailGrns',[GoodsController::class,'allTimeGoodDetailGrns']);
    Route::post('mostProfitedGoodDetail',[GoodsController::class,'mostProfitedGoodDetail']);
    Route::post('logout',[UserController::class,'logout']);
    Route::get('searchAll/{inputText}',[UserController::class,'searchAll']);
    Route::get('singleItem/{table}/{id}',[UserController::class,'singleItem']);
    Route::get('productTransactionCount',[GoodsController::class,'productTransactionCount']);
    Route::get('test',(function(){return true;}));

});
Route::get('/',[UserController::class,'invalidRequest'])->name('error');
Route::post('login',[UserController::class,'login']);
   
