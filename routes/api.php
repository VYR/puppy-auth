<?php

use App\Http\Controllers\API\MultipleUploadController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\SchemeController;
use App\Http\Controllers\SchemeTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'except' => ['login','register']
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/add-update-users/{id?}', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);  
    Route::post('/multiple-image-upload', [AuthController::class, 'upload']); 
    Route::post('/multiple-image', [AuthController::class, 'store']); 
    Route::get('/users/{id?}', [AuthController::class, 'read']);  
    /**Scheme Types */    
    Route::post('/add-update-scheme-types/{id?}', [SchemeTypeController::class, 'create']);
    Route::get('/scheme-types/{id?}', [SchemeTypeController::class, 'read']); 
    /**Schemes */    
    Route::post('/add-update-schemes/{id?}', [SchemeController::class, 'create']);
    Route::get('/schemes/{id?}', [SchemeController::class, 'read']);  
    /**Common API */ 
    Route::delete('/common/delete/{id?}/{type?}', [CommonController::class, 'delete']);
    Route::get('/common/graph', [CommonController::class, 'getGraph']);
});
Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact info@website.com','status_code' => 404], 404);
});