<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Worked, by success'
    ],200);
});
Route::post('/login',[AuthController::class,'login']);



Route::get('/test', function () {
    return response()->json([
        'message' => 'API works',
    ]);
});

Route::get('/books', function () {
    return response()->json([
        ['id' => 1, 'title' => 'Book 1'],
        ['id' => 2, 'title' => 'Book 2'],
    ]);
});  
