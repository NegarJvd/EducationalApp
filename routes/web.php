<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Panel\AdminController;
use App\Http\Controllers\Panel\PermissionController;
use App\Http\Controllers\Panel\RoleController;
use App\Http\Controllers\Panel\UserController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return redirect()->to('/login');
});

Auth::routes(['register' => false, 'reset' => false]);
Route::get('forgotPasswordView', [LoginController::class, 'forgotPasswordView']);
Route::post('forgotPassword', [LoginController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('resetPassword', [LoginController::class, 'resetPassword'])->name('resetPassword');

Route::get('/panel', [App\Http\Controllers\Panel\HomeController::class, 'index'])->name('panel');

Route::group(['middleware' => ['auth:web'], 'prefix' => 'panel', 'as' => 'panel.'], function() {
    Route::get('dashboard_info_counts', [App\Http\Controllers\Panel\HomeController::class, 'dashboard_info_counts']);
    Route::get('profile', [App\Http\Controllers\Panel\HomeController::class, 'show_profile']);
    Route::patch('profile', [App\Http\Controllers\Panel\HomeController::class, 'update_profile'])->name('profile');
////    Route::post('store_device', [HomeController::class, 'storeDevice']);
////    Route::post('store_fcm', [HomeController::class, 'storeFCM']);
////    Route::get('message_list', [\App\Http\Controllers\UserController::class, 'messages_list']);

    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);

    Route::resource('admins', AdminController::class);
    Route::post('change_admin_role', [AdminController::class, 'change_admin_role'])->name('change_admin_role');
    Route::get('change_admin_status/{user_id}', [AdminController::class, 'change_admin_status'])->name('change_admin_status');

    Route::resource('users', UserController::class);
    Route::get('change_user_status/{user_id}', [UserController::class, 'change_user_status'])->name('change_user_status');
////    Route::resource('/addresses', AddressesController::class)->only('show', 'store', 'update', 'destroy');
//
////    Route::resource('categories', CategoryController::class);
////    Route::resource('products', ProductController::class);
////    Route::apiResource('comments', CommentController::class)->except('destroy');
////
////    Route::resource('messages', MessageController::class)->only('index', 'show', 'create', 'store');
////    Route::resource('tickets', TicketController::class)->only('index', 'edit', 'update');
//
//    //search with select2
//    Route::get('admins_list', [AdminController::class, 'search_in_admins_list_json']);
//    Route::get('users_list', [UserController::class, 'search_in_users_list_json']);
//    Route::get('cityList', [HomeController::class, 'cityList']);
//
//    //upload file.
//    Route::post('upload', [UploadController::class, 'upload_file']);
//    Route::delete('delete_file/{upload_id}', [UploadController::class, 'delete_file']);
//
////    Route::get('options', [OptionsController::class, 'options'])->name('options');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
