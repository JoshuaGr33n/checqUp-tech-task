<?php

use App\Interfaces\Http\Controllers\Users\UserController;


Route::apiResource('users', UserController::class);
