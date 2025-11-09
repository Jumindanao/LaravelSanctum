<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//Public Route
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


//Private Route
Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::resource('/tasks',TaskController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});
