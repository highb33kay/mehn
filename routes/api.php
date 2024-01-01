<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankStatementController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
	Route::post('/analyze-bank-statement', [BankStatementController::class, 'analyze']);
});

// Register: Register a new user
Route::post('register', [AuthController::class, 'register']);

// Login: Login an existing user
Route::post('login', [AuthController::class, 'login']);

// Logout: Logout an existing user
Route::post('logout', [AuthController::class, 'logout']);

// get an authenticated user
Route::get('user', [AuthController::class, 'user']);
