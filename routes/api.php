<?php

use App\Http\Controllers\{ComplaintController,
    EndUserController,
    StudentController,
    AuthController,
    GroupController,
    GroupSessionController,
    StaffController,
    SubscriptionController,
    TeacherController};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware(['jwt.verify'])->group(function () {
        Route::post('logout', [AuthController::class,'logout']);
        Route::post('update', [AuthController::class,'update']);
    });
});

Route::group(['prefix' => 'admin', 'middleware' => ['api', 'jwt.verify', 'roles:Admin']], function () {
    Route::prefix('staff')->group(function () {
        Route::get('index', [StaffController::class, 'index']);
        Route::post('create', [StaffController::class, 'create']);
        Route::get('show', [StaffController::class, 'show']);
        Route::post('update', [StaffController::class, 'update']);
        Route::post('delete', [StaffController::class, 'delete']);
    });
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['api', 'jwt.verify', 'roles:Admin,Support,Secretary']], function () {
    Route::prefix('teacher')->group(function () {
        Route::get('index', [TeacherController::class, 'index']);
        Route::post('create', [TeacherController::class, 'create']);
        Route::get('show', [TeacherController::class, 'show']);
        Route::post('update', [TeacherController::class, 'update']);
        Route::post('delete', [TeacherController::class, 'delete']);
    });
    Route::prefix('student')->group(function () {
        Route::get('index', [StudentController::class, 'index']);
        Route::post('create', [StudentController::class, 'create']);
        Route::get('show', [StudentController::class, 'show']);
        Route::post('update', [StudentController::class, 'update']);
        Route::post('delete', [StudentController::class, 'delete']);
        Route::get('limitCount', [SubscriptionController::class, 'limitCount']);
        Route::get('closedCount', [SubscriptionController::class, 'closedCount']);

    });
    Route::prefix('group')->group(function () {
        Route::get('index', [GroupController::class, 'index']);
        Route::post('create', [GroupController::class, 'create']);
        Route::get('show', [GroupController::class, 'show']);
        Route::post('update', [GroupController::class, 'update']);
        Route::post('delete', [GroupController::class, 'delete']);

        Route::prefix('session')->group(function () {
            Route::get('index', [GroupSessionController::class, 'index']);
            Route::post('create', [GroupSessionController::class, 'create']);
            Route::get('show', [GroupSessionController::class, 'show']);
            Route::post('update', [GroupSessionController::class, 'update']);
            Route::post('delete', [GroupSessionController::class, 'delete']);
        });
    });

    Route::prefix('complaint')->group(function () {
        Route::get('index', [ComplaintController::class, 'index']);
        Route::post('show', [ComplaintController::class, 'show']);
        Route::post('delete', [ComplaintController::class, 'delete']);
    });
});

Route::group(['prefix' => 'enduser', 'middleware' => ['api', 'jwt.verify', 'roles:Teacher,Student']], function () {
//    Route::prefix('groups')->group(function () {
        Route::get('index', [EndUserController::class, 'index']);
//    });
});
