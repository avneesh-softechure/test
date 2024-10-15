<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

Route::get('/users', [ApiController::class, 'index']);
Route::post('/save-data', [ApiController::class, 'store'])->name('store');
