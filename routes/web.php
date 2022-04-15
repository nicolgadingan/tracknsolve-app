<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('/dashboard');
});

Auth::routes();
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::resource('tickets', App\Http\Controllers\TicketsController::class);
Route::resource('users', App\Http\Controllers\UsersController::class);
Route::resource('groups', App\Http\Controllers\GroupsController::class);
Route::get('/reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports');
Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings');

Route::get('send-mail', [App\Http\Controllers\MailsController::class, 'index']);

Route::get('/user/verify/{token}', [App\Http\Controllers\UsersController::class, 'verify']);