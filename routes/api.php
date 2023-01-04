<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;

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



//create new user
Route::middleware('guest')->post('/user/register', [UserController::class, 'register']);
Route::middleware('guest')->post('/user/login', [UserController::class, 'login']);

Route::middleware('guest')->post('/user/request-password', [UserController::class, 'requestPassword']);
Route::middleware('guest')->post('/user/validate-otp', [UserController::class, 'validateOtp']);
Route::middleware('guest')->post('/user/reset-password', [UserController::class, 'resetPassword']);




Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::resource('contacts', ContactController::class);


    Route::post('/user/logout', [UserController::class, 'logout']);
    Route::post('/user/logout-all', [UserController::class, 'logoutAll']);

    Route::delete('/user/delete', [UserController::class, 'delete']);
});