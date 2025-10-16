<?php

use Illuminate\Support\Facades\Route;
use Modules\SUPPORT\Http\Controllers\SUPPORTController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'support', 'as' => 'support.'], function () {
    Route::get('/', [SUPPORTController::class, 'dashboard'])->name('dashboard');
    Route::get('/user', [SUPPORTController::class, 'userSupport'])->name('user');
    Route::get('/dealer', [SUPPORTController::class, 'dealerSupport'])->name('dealer');
    Route::post('/search-users', [SUPPORTController::class, 'searchUsers'])->name('search.users');
    Route::post('/search-dealers', [SUPPORTController::class, 'searchDealers'])->name('search.dealers');
    Route::get('/user/{id}', [SUPPORTController::class, 'showUser'])->name('user.show');
    Route::get('/dealer/{id}', [SUPPORTController::class, 'showDealer'])->name('dealer.show');
    
    // User Management Routes
    Route::get('/users/create', [SUPPORTController::class, 'createUser'])->name('users.create');
    Route::post('/users', [SUPPORTController::class, 'storeUser'])->name('users.store');
    Route::get('/users', [SUPPORTController::class, 'users'])->name('users.index');
});
