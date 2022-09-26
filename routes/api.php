<?php

use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\TransactionController;
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

Route::get('category', [CategoryController::class, 'all']);
Route::post('addcategory', [CategoryController::class, 'addcategory']);
Route::post('updatecategory', [CategoryController::class, 'updatecategory']);
Route::post('deletecategory', [CategoryController::class, 'deletecategory']);

Route::get('book', [BookController::class, 'all']);
Route::post('addbook', [BookController::class, 'addbook']);
Route::post('updatebook', [BookController::class, 'updatebook']);
Route::post('deletebook', [BookController::class, 'deletebook']);

Route::get('transaksi', [TransactionController::class, 'all']);
Route::post('pinjam', [TransactionController::class, 'pinjam']);
Route::post('kembali', [TransactionController::class, 'kembali']);