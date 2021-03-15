<?php

use App\Http\Controllers\{
    StudentController,
    AuthController,
    StaffController
};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('subjects/{student_id}', [StudentController::class, 'getSubjects']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class,'logout']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'staff'
], function () {
    Route::get('index', [StaffController::class, 'index']);
    Route::post('create', [StaffController::class, 'create']);
    Route::post('update', [StaffController::class, 'update']);
    Route::post('delete', [StaffController::class, 'delete']);
});
