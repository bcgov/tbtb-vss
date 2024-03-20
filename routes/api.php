<?php

use App\Http\Controllers\TestController;
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
Route::group(['middleware' => 'apiauth'], function () {
    Route::get('/test', [TestController::class, 'test']);
//    Route::post('/app', [TestController::class, 'app']);
});


Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
