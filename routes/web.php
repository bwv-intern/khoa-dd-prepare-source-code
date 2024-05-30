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
    Route::get('/user', [UserController::class, 'viewAdminUserSearch'])->name('USER_SEARCH')
        ->middleware(['nocache']);
    Route::post('/user/submit-search', [UserController::class, 'submitAdminUserSearch'])->name('USER_SEARCH_SUBMIT');

    Route::get('/user-delete/{id}', [UserController::class, 'submitAdminUserDelete'])->name('USER_DELETE');

    Route::prefix('common')->name('COMMON_')->group(function () {
        Route::get('resetSearch', [CommonController::class, 'resetSearch'])->name('RESET_SEARCH');
    });

    Route::get('user-export', [UserController::class, 'exportAdminUser'])->name('USER_EXPORT');

    Route::get('/user/add', [UserController::class, 'viewAdminUserAdd'])->name('USER_ADD')
        ->middleware(['nocache']);
    Route::post('/user/add-submit', [UserController::class, 'submitAdminUserAdd'])->name('USER_ADD_SUBMIT')
        ->middleware(['nocache']);

    Route::get('/user/edit/{id}', [UserController::class, 'viewAdminUserEdit'])->name('USER_EDIT')
        ->middleware(['nocache']);
    Route::post('/user/edit-submit/{id}', [UserController::class, 'submitAdminUserEdit'])->name('USER_EDIT_SUBMIT')
        ->middleware(['nocache']);

    Route::post('/user-import', [UserController::class, 'submitAdminUserImport'])->name('USER_IMPORT_SUBMIT');
});
