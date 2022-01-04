<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UserController;
use App\http\Controllers\AuthController;

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
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/verify-email',[UserController::class, 'verify_email']);
Route::post('/register-user',[AuthController::class, 'register']);
Route::post('/invite',[MailController::class, 'sent_registration_email'])->middleware('auth:sanctum');
Route::post('/login-user',[AuthController::class, 'login']);
Route::post('/update-profile',[UserController::class, 'update_profile'])->middleware('auth:sanctum');

