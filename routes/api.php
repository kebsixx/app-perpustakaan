<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\StatsController;
use Illuminate\Support\Facades\Route;

Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::post('/books', [BookController::class, 'store']);
Route::put('/books/{id}', [BookController::class, 'update']);
Route::delete('/books/{id}', [BookController::class, 'destroy']);

Route::get('/members', [MemberController::class, 'index']);
Route::get('/members/{id}', [MemberController::class, 'show']);

Route::get('/loans', [LoanController::class, 'index']);
Route::post('/loans', [LoanController::class, 'store']);
Route::put('/loans/{id}/kembalikan', [LoanController::class, 'kembalikan']);

Route::get('/stats', [StatsController::class, 'index']);

// php artisan make:controller Api/BookController
// php artisan make:controller Api/MemberController
// php artisan make:controller Api/LoanController
// php artisan make:controller Api/StatsController
// php artisan make:resource BookResource
// php artisan make:resource MemberResource
// php artisan make:resource LoanResource
