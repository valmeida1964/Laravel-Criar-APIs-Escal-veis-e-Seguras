<?php

use App\Http\Middleware\MaintenanceModeMiddleware;
use App\Services\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // check if the API is in maintenance mode
        $middleware->api(prepend:[
            MaintenanceModeMiddleware::class
        ]);

        // rate limiting middleware using the default api rate limiter
        $middleware->api(prepend: [
            ThrottleRequests::class.':api' // use the 'api' rate limiter
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        ); 

        // custom exception for rate Limiting
        $exceptions->render(function(ThrottleRequestsException $e, $request){
            return ApiResponse::error('Too many requests', 429);
        });

        // capture validation errors (ValidationException)
        $exceptions->render(function(ValidationException $e, Request $request){
            if ($request->is('api/*')) {
                return ApiResponse::error(
                    code: 422,
                    errors: $e->errors()
                );
            }
        });

        // exception for everything else ?!
        $exceptions->render(function(\Exception $e, Request $request){
            if ($request->is('api/*')) {
                return ApiResponse::error(
                    message: "An unexpected error occurred",
                    code: 500,
                    errors: [$e->getMessage()]
                );
            }
        });
    })->create();
