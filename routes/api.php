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
    Route::apiResource('good',GoodsController::class)->except(['store']);
    Route::apiResource('admin',AdminController::class);
    Route::get('goodsCount',[GoodsController::class,'goodsCount']);
    Route::post('newSale',[GoodsController::class,'newSale']);
    Route::post('newGrn',[GoodsController::class,'newGrn']);
    Route::get('allGoodsWithinPeriod',[GoodsController::class,'getAllWithinPeriod']);
    Route::apiResource('builtInTask',BuiltInTasksController::class);
    Route::post('goodDetail/{type}',[GoodsController::class,'addGoodDetail']);
    Route::get('goodDetails/{type}',[GoodsController::class,'viewGoodDetails']);
    Route::delete('goodDetail/{type}/{id}',[GoodsController::class,'deleteGoodDetail']);
    Route::put('goodDetail/{type}/{id}',[GoodsController::class,'updateGoodDetail']);
    Route::get('sales',[GoodsController::class,'allSales']);
    Route::get('grns',[GoodsController::class,'allGrns']);
    Route::get('profitLost',[GoodsController::class,'profitLost']);
    Route::get('allTimeSales',[GoodsController::class,'allTimeSales']);
    Route::get('allTimeGrns',[GoodsController::class,'allTimeGrns']);
    Route::get('allGoodDetailSales',[GoodsController::class,'allGoodDetailSales']);
    Route::get('allGoodDetailGrns',[GoodsController::class,'allGoodDetailGrns']);
    Route::get('allTimeGoodDetailSales',[GoodsController::class,'allTimeGoodDetailSales']);
    Route::get('allTimeGoodDetailGrns',[GoodsController::class,'allTimeGoodDetailGrns']);
    Route::get('mostProfitedGoodDetail',[GoodsController::class,'mostProfitedGoodDetail']);
    Route::get('logout',[UserController::class,'logout']);
    Route::get('searchAll/{inputText}',[UserController::class,'searchAll']);
    Route::get('singleItem/{table}/{id}',[UserController::class,'singleItem']);
    Route::get('productTransactionCount',[GoodsController::class,'productTransactionCount']);
    Route::get('newGoodSearch/{type}/{inputText}',[GoodsController::class,'newGoodSearch']);
    Route::get('peopleData',[UserController::class,'peopleData']);
    Route::get('test',(function(){return true;}));
    Route::post('profession',[AdminController::class,'newProfession']);
    Route::get('getProfessions',[AdminController::class,'getProfessions']);

});
Route::get('/',[UserController::class,'invalidRequest'])->name('error');
Route::post('login',[UserController::class,'login']);
   
