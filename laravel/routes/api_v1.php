<?php

use App\Http\Controllers\v1\AdminController;
use App\Http\Controllers\v1\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/user/account/register',[UserController::class,'register']);
Route::post('/user/account/login',[UserController::class,'login']);
Route::get('/social/{social}',[UserController::class,'getSocialLink']);
Route::post('social/{social}/login',[UserController::class,'loginSocial']);
Route::post('social/{social}/register',[UserController::class,'registerSocial']);
Route::middleware(['auth:sanctum','ability:user'])->group(function(){
    Route::get('/user/account/logout',[UserController::class,'logout']);
    Route::patch('/user/account/change-password',[UserController::class,'changePassword']);
});

Route::middleware(['auth:sanctum','ability:admin'])->group(function(){
    Route::patch('/user/account/ban/{user}',[UserController::class,'ban']);
    Route::patch('/user/account/unban/{user}',[UserController::class,'unBan']);
    Route::get('/admin/account/logout',[AdminController::class,'logout']);
    Route::patch('/admin/account/change-password',[AdminController::class,'changePassword']);
});
Route::post('/admin/account/login',[AdminController::class,'login']);