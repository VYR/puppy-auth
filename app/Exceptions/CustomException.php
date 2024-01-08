<?php
 
namespace App\Exceptions;
 
use Exception;
use Throwable;
 
class CustomException extends Exception
{
    /** 
* Report the exception. 
* 
* @return void 
*/
    public function report()
    {
        
    }

    public function render($request,Throwable $e,)
    {
        return response()->json([
            'message' => $e->getMessage(),
            'status' => $e->getCode(),
            //'trace' => $e->getTrace(),
        ], 404);
    }
}