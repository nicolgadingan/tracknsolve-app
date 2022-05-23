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

// TICKETS
Route::resource('tickets', App\Http\Controllers\TicketsController::class)->except(['store']);
Route::put('/tickets/{ticket}/get', [App\Http\Controllers\TicketsController::class, 'get']);
Route::put('/tickets/{ticket}/resolve', [App\Http\Controllers\TicketsController::class, 'resolve']);

Route::resource('users', App\Http\Controllers\UsersController::class)->except(['edit', 'update']);

// GROUPS
Route::resource('groups', App\Http\Controllers\GroupsController::class)->except(['store', 'edit', 'update']);
Route::put('/groups/{group}/deactivate', [App\Http\Controllers\GroupsController::class, 'deactivate']);
Route::put('/groups/{group}/activate', [App\Http\Controllers\GroupsController::class, 'activate']);

Route::get('/reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports');
Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings');

Route::get('send-mail', [App\Http\Controllers\MailsController::class, 'index']);

Route::get('/user/verify/{token}', [App\Http\Controllers\AccessesController::class, 'verification']);
Route::post('/user/verify', [App\Http\Controllers\AccessesController::class, 'verify'])->name('verify');

Route::get('/hello', function() {
    return view('mails/hello');
});