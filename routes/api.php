<?php

use App\Http\Controllers\AttentionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ExternalController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TypeAttentionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/sign-in', [AuthController::class, 'signIn']);
    Route::post('/sign-out', [AuthController::class, 'signOut'])->middleware('auth:sanctum');
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
});


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'offices'], function () {
    Route::get('/', [OfficeController::class, 'index'])->middleware('can:offices');
    Route::post('/', [OfficeController::class, 'store'])->middleware('can:offices.create');
    Route::put('/{office}', [OfficeController::class, 'update'])->middleware('can:offices.update');

    Route::get('/options', [OfficeController::class, 'options']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'types-attention'], function () {
    Route::get('/', [TypeAttentionController::class, 'index'])->middleware('can:type-attentions');
    Route::post('/', [TypeAttentionController::class, 'store'])->middleware('can:type-attentions.create');
    Route::put('/{typeAttention}', [TypeAttentionController::class, 'update'])->middleware('can:type-attentions.update');

    Route::get('/options', [TypeAttentionController::class, 'options']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'professors'], function () {
    Route::get('/', [ProfessorController::class, 'index'])->middleware('can:professors');
    Route::post('/', [ProfessorController::class, 'store'])->middleware('can:professors.create');
    Route::put('/{professor}', [ProfessorController::class, 'update'])->middleware('can:professors.update');
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'students'], function () {
    Route::get('/', [StudentController::class, 'index'])->middleware('can:students');
    Route::post('/', [StudentController::class, 'store'])->middleware('can:students.create');
    Route::put('/{student}', [StudentController::class, 'update'])->middleware('can:students.update');

    Route::post('/receive/{document}', [StudentController::class, 'receiveStudent']);
    Route::get('/by-document/{document}', [StudentController::class, 'getByDocument']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'workers'], function () {
    Route::get('/', [WorkerController::class, 'index'])->middleware('can:workers');
    Route::post('/', [WorkerController::class, 'store'])->middleware('can:workers.create');
    Route::put('/{worker}', [WorkerController::class, 'update'])->middleware('can:workers.update');
});


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'externals'], function () {
    Route::get('/', [ExternalController::class, 'index'])->middleware('can:externals');
    Route::post('/', [ExternalController::class, 'store'])->middleware('can:externals.create');
    Route::put('/{external}', [ExternalController::class, 'update'])->middleware('can:externals.update');
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'users'], function () {
    Route::get('/', [UserController::class, 'index'])->middleware('can:users');
    Route::post('/', [UserController::class, 'store'])->middleware('can:users.create');
    Route::put('/{user}', [UserController::class, 'update'])->middleware('can:users.update');
    Route::get('/offices', [UserController::class, 'offices']);
    Route::get('/roles', [UserController::class, 'roles']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'roles'], function () {
    Route::get('/', [RoleController::class, 'index'])->middleware('can:roles');
    Route::post('/', [RoleController::class, 'store'])->middleware('can:roles.create');
    Route::put('/{role}', [RoleController::class, 'update'])->middleware('can:roles.update');
    Route::post('/{role}/permissions', [RoleController::class, 'assignPermissions'])->middleware('can:roles.assign-permissions');
    Route::get('/permissions', [RoleController::class, 'permissions'])->middleware('can:roles');
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'attentions'], function () {
    Route::get('/', [AttentionController::class, 'index']);
    Route::get('/last', [AttentionController::class, 'last']);
    Route::post('/{type}', [AttentionController::class, 'store']);
    // Route::put('/{attention}', [AttentionController::class, 'update'])->middleware('can:attentions.update');
});
