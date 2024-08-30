<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ProductPurchaseController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/products/buy',[ProductPurchaseController::class,'buy'])->middleware('auth:api');
Route::get('/products/info/{code}',[ProductPurchaseController::class,'getProductInfo'])->middleware('auth:api');
Route::post('/products/loan',[ProductPurchaseController::class,'loan'])->middleware('auth:api');
Route::post('/products/extend-loan',[ProductPurchaseController::class,'extendLoan'])->middleware('auth:api');

Route::post('register', [AuthenticationController::class, 'register'])->name('register');
Route::post('login', [AuthenticationController::class, 'login'])->name('login');
