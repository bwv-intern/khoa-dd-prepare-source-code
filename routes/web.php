<?php

use App\Http\Controllers\{
    AuthController,
    CommonController,
    TopController,
    UserController
};
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
    return view('welcome');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('nocache');
    Route::post('/login/handleLogin', [AuthController::class, 'handleLogin'])->name('auth.handleLogin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->prefix('admin')->name('ADMIN_')->group(function () {
    Route::get('/', [TopController::class, 'index'])->name('TOP')->middleware(['nocache', 'authorize_user_flg:admin,support']);
    Route::get('/user', [UserController::class, 'viewAdminUserSearch'])->name('USER_SEARCH')->middleware(['nocache', 'authorize_user_flg:admin']);
    Route::post('/user/submit-search', [UserController::class, 'submitAdminUserSearch'])->name('USER_SEARCH_SUBMIT')->middleware(['authorize_user_flg:admin']);

    Route::get('/user-delete/{id}', [UserController::class, 'submitAdminUserDelete'])->name('USER_DELETE')->middleware(['authorize_user_flg:admin']);

    Route::prefix('common')->name('COMMON_')->group(function () {
        Route::get('resetSearch', [CommonController::class, 'resetSearch'])->name('RESET_SEARCH')->middleware(['authorize_user_flg:admin']);
    });

    Route::get('user-export', [UserController::class, 'exportAdminUser'])->name('USER_EXPORT')->middleware(['authorize_user_flg:admin']);
});
