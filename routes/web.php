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

Route::middleware(['auth', 'authorize_user_flg:admin'])->prefix('admin')->name('ADMIN_')->group(function () {
    Route::get('/', [TopController::class, 'index'])->name('TOP')
        ->withoutMiddleware(['authorize_user_flg:admin'])
        ->middleware(['nocache', 'authorize_user_flg:admin,support']);
    Route::prefix('common')->name('COMMON_')->group(function () {
        Route::get('resetSearch', [CommonController::class, 'resetSearch'])->name('RESET_SEARCH');
    });
    Route::prefix('user')->name('USER_')->group(function () {
        Route::get('/', [UserController::class, 'viewAdminUserSearch'])->name('SEARCH')
            ->middleware(['nocache']);
        Route::post('/submit-search', [UserController::class, 'submitAdminUserSearch'])->name('SEARCH_SUBMIT');

        Route::get('/delete/{id}', [UserController::class, 'submitAdminUserDelete'])->name('DELETE');

        Route::get('/export', [UserController::class, 'exportAdminUser'])->name('EXPORT');

        Route::get('/add', [UserController::class, 'viewAdminUserAdd'])->name('ADD')
            ->middleware(['nocache']);
        Route::post('/add', [UserController::class, 'submitAdminUserAdd'])->name('ADD_SUBMIT')
            ->middleware(['nocache']);

        Route::get('/edit/{id}', [UserController::class, 'viewAdminUserEdit'])->name('EDIT')
            ->middleware(['nocache']);
        Route::post('/edit/{id}', [UserController::class, 'submitAdminUserEdit'])->name('EDIT_SUBMIT')
            ->middleware(['nocache']);

        Route::post('/import', [UserController::class, 'submitAdminUserImport'])->name('IMPORT_SUBMIT');
    });
});
