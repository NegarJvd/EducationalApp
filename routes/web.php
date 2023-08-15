<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Panel\ActionController;
use App\Http\Controllers\Panel\AdminController;
use App\Http\Controllers\Panel\ContentController;
use App\Http\Controllers\Panel\HomeController;
use App\Http\Controllers\Panel\RoleController;
use App\Http\Controllers\Panel\TicketController;
use App\Http\Controllers\Panel\UserController;
use App\Http\Controllers\Panel\UsersContentController;
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

    Route::resource('roles', RoleController::class);

    Route::resource('admins', AdminController::class)->except('destroy');
    Route::post('change_admin_role', [AdminController::class, 'change_admin_role'])->name('change_admin_role');
    Route::get('change_admin_status/{user_id}', [AdminController::class, 'change_admin_status'])->name('change_admin_status');

    Route::resource('users', UserController::class);

    Route::resource('contents', ContentController::class);
    Route::resource('tickets', TicketController::class)->only('index', 'show', 'store');

    //charts
    Route::get('evaluation', [ActionController::class, 'evaluation']);

    //users contents
    Route::get('get_each_contents_clusters_list/{content_id}', [UsersContentController::class, 'get_each_contents_clusters_list']);
    Route::post('add_content_for_user', [UsersContentController::class, 'add_content_for_user']);
    Route::delete('delete_content_for_user', [UsersContentController::class, 'delete_content_for_user']);
//
//    //search with select2
//    Route::get('admins_list', [AdminController::class, 'search_in_admins_list_json']);
//    Route::get('users_list', [UserController::class, 'search_in_users_list_json']);

//
//    //upload file.
    Route::post('upload', [HomeController::class, 'upload_file']);
//    Route::delete('delete_file/{upload_id}', [UploadController::class, 'delete_file']);
//
////    Route::get('options', [OptionsController::class, 'options'])->name('options');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
