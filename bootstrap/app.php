<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Domain\Users\Exceptions\InvalidUserIdException;
use App\Domain\Users\Exceptions\UserNotFoundException;
use Illuminate\Http\Request;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (InvalidUserIdException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        });

        $exceptions->render(function (UserNotFoundException $e, Request $request) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 404);
        });
    })->create();
