<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Route::prefix('api')->group(function () {
//    Route::group(['middleware' => 'apiauth'], function () {
//        Route::get('/test', [TestController::class, 'test']);
//    });
//});

Route::get('/login', function () {
    return view('welcome');
})->name('login');


Route::get('/', function () {
    return view('welcome');
});
