<?php

use App\Http\Controllers\App\AuthController;
use App\Http\Controllers\App\ContentController;
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

Route::post('login', [AuthController::class, 'login_with_password']);
Route::post('login_first_step', [AuthController::class, 'login_first_step']);
Route::post('login_second_step', [AuthController::class, 'login_second_step']);
Route::post('forgot_password', [AuthController::class, 'forgotPassword']);
Route::post('reset_password', [AuthController::class, 'resetPassword']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    //user
    Route::get('profile', [AuthController::class, 'profile']);
    Route::put('profile', [AuthController::class, 'update_profile']);
    Route::put('change_password', [AuthController::class, 'changePassword']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('therapist_info', [AuthController::class, 'therapist_info']);

    //contents
    Route::get('contents', [ContentController::class, 'contents_list']);
    Route::get('contents/{content_id}', [ContentController::class, 'cluster_detail']);
    Route::post('store_action', [ContentController::class, 'store_action']);
});
