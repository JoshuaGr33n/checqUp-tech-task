<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\Users\UserController;


Route::prefix('v1')->group(function () {
    Route::apiResource('users', UserController::class);
});