<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    CommonController,
    TopController,
    UserController
};

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

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login/handleLogin', [AuthController::class, 'handleLogin'])->name('auth.handleLogin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [TopController::class, 'index'])->name('top.index');
    Route::get('/user', [UserController::class, 'usr01'])->name('user.usr01');
    Route::post('/user/handleUsr01', [UserController::class, 'handleUsr01'])->name('user.handleUsr01');

    Route::prefix('common')->as('common.')->group(function () {
        Route::get('resetSearch', [CommonController::class, 'resetSearch'])->name('resetSearch');
    });
});
