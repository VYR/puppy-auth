<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
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
               /* if ($e instanceof ModelNotFoundException) {
                    return response()->json([
                        'message' => 'Entry for '.str_replace('App', '', $e->getModel()).' not found',                        
                        'status_code' => $e->getCode(),
                    ], 404);
                }
                if ($e instanceof CustomException)  {
                    return $e->render($request,$e);
                }
                return response()->json([
                    'message' => $e->getMessage(),
                    'status_code' => $e->getCode(),
                    //'trace' => $e->getTrace(),
                ], 404);*/
                //if (!$request->expectsJson() && ($e instanceof ValidationException)) {
                    $response = [
                        'message' => $e->getMessage(),
                        'status_code' => 400,
                    ];
            
                    if ($e instanceof HttpException) {
                        $response['message'] = Response::$statusTexts[$e->getStatusCode()];
                        $response['status_code'] = $e->getStatusCode();
                    } else if ($e instanceof ModelNotFoundException) {
                        $response['message'] = Response::$statusTexts[Response::HTTP_NOT_FOUND];
                        $response['status_code'] = Response::HTTP_NOT_FOUND;
                    } 
            
                    return response()->json([
                        'status'      => 'failed',
                        'status_code' => $response['status_code'],
                        'massage'     => $response['message'],
                    ], $response['status_code']);
                //}
            
                //return parent::render($request, $e);
            }
        });       
    }
}
