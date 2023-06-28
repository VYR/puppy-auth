<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                if ($e instanceof ModelNotFoundException) {
                    return response()->json([
                        'message' => 'Entry for '.str_replace('App', '', $e->getModel()).' not found',                        
                        'status_code' => $e->getCode(),
                    ], 404);
                }
                return response()->json([
                    'message' => $e->getMessage(),
                    'status_code' => $e->getCode(),
                    'trace' => $e->getTrace(),
                ], 404);
            }
        });       
    }
}
