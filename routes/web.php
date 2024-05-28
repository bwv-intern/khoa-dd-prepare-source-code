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

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [TopController::class, 'index'])->name('top.index')->middleware(['nocache', 'authorize_user_flg:admin,support']);
    Route::get('/user', [UserController::class, 'usr01'])->name('user.usr01')->middleware(['nocache', 'authorize_user_flg:admin']);
    Route::post('/user/handleUsr01', [UserController::class, 'handleUsr01'])->name('user.handleUsr01')->middleware(['authorize_user_flg:admin']);

    Route::prefix('common')->as('common.')->group(function () {
        Route::get('resetSearch', [CommonController::class, 'resetSearch'])->name('resetSearch')->middleware(['authorize_user_flg:admin']);
    });
});
