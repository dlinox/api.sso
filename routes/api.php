<?php

use App\Http\Controllers\AttentionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ExternalController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\ReportController;
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

    Route::post('/items', [OfficeController::class, 'items']);

    Route::get('/', [OfficeController::class, 'index'])->middleware('can:offices');
    Route::post('/', [OfficeController::class, 'store'])->middleware('can:offices.create');
    Route::put('/{office}', [OfficeController::class, 'update'])->middleware('can:offices.update');

    Route::get('/options', [OfficeController::class, 'options']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'types-attention'], function () {

    Route::post('/items', [TypeAttentionController::class, 'items']);

    Route::get('/', [TypeAttentionController::class, 'index'])->middleware('can:type-attentions');
    Route::post('/', [TypeAttentionController::class, 'store'])->middleware('can:type-attentions.create');
    Route::put('/{typeAttention}', [TypeAttentionController::class, 'update'])->middleware('can:type-attentions.update');

    Route::get('/options', [TypeAttentionController::class, 'options']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'professors'], function () {

    Route::post('/items', [ProfessorController::class, 'items']);

    Route::get('/', [ProfessorController::class, 'index'])->middleware('can:professors');
    Route::post('/', [ProfessorController::class, 'store'])->middleware('can:professors.create');
    Route::put('/{professor}', [ProfessorController::class, 'update'])->middleware('can:professors.update');
    Route::get('/search/{term}', [ProfessorController::class, 'search']);
    Route::get('/by-document/{document}', [ProfessorController::class, 'getByDocument']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'students'], function () {

    //items
    Route::post('/items', [StudentController::class, 'items']);


    Route::get('/', [StudentController::class, 'index'])->middleware('can:students');
    Route::post('/', [StudentController::class, 'store'])->middleware('can:students.create');
    Route::put('/{student}', [StudentController::class, 'update'])->middleware('can:students.update');

    Route::post('/receive/{document}', [StudentController::class, 'receiveStudent']);
    Route::get('/by-document/{document}', [StudentController::class, 'getByDocument']);
});




Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'workers'], function () {

    Route::post('/items', [WorkerController::class, 'items']);

    Route::get('/', [WorkerController::class, 'index'])->middleware('can:workers');
    Route::post('/', [WorkerController::class, 'store'])->middleware('can:workers.create');
    Route::put('/{worker}', [WorkerController::class, 'update'])->middleware('can:workers.update');
    Route::get('/offices', [WorkerController::class, 'offices']);

    Route::get('/search/{term}', [WorkerController::class, 'search']);
    Route::get('/by-document/{document}', [WorkerController::class, 'getByDocument']);
});


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'externals'], function () {
    Route::get('/', [ExternalController::class, 'index'])->middleware('can:externals');
    Route::post('/', [ExternalController::class, 'store'])->middleware('can:externals.create');
    Route::put('/{external}', [ExternalController::class, 'update'])->middleware('can:externals.update');

    Route::get('/search/{term}', [ExternalController::class, 'search']);
    Route::get('/by-document/{document}', [ExternalController::class, 'getByDocument']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'users'], function () {

    //items
    Route::post('/items', [UserController::class, 'items']);

    Route::get('/', [UserController::class, 'index'])->middleware('can:users');
    Route::post('/', [UserController::class, 'store'])->middleware('can:users.create');
    Route::put('/{user}', [UserController::class, 'update'])->middleware('can:users.update');
    Route::get('/offices', [UserController::class, 'offices']);
    Route::get('/roles', [UserController::class, 'roles']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'roles'], function () {

    //imtes
    Route::post('/items', [RoleController::class, 'items']);

    Route::get('/', [RoleController::class, 'index'])->middleware('can:roles');
    Route::post('/', [RoleController::class, 'store'])->middleware('can:roles.create');
    Route::put('/{role}', [RoleController::class, 'update'])->middleware('can:roles.update');
    Route::post('/{role}/permissions', [RoleController::class, 'assignPermissions'])->middleware('can:roles.assign-permissions');
    Route::get('/permissions', [RoleController::class, 'permissions'])->middleware('can:roles');
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'attentions'], function () {

    //itemsStudent
    Route::post('/items-students/{type}', [AttentionController::class, 'itemsStudents']);

    Route::post('/student/receive/{document}', [StudentController::class, 'receiveStudent']);
    Route::get('/student/by-document/{document}', [StudentController::class, 'getByDocument']);

    Route::get('/professor/search/{term}', [ProfessorController::class, 'search']);
    ///attentions/professor/by-document/
    Route::get('/professor/by-document/{document}', [ProfessorController::class, 'getByDocument']);

    ///attentions/worker/by-document/
    Route::get('/worker/search/{term}', [WorkerController::class, 'search']);
    Route::get('/worker/by-document/{document}', [WorkerController::class, 'getByDocument']);

    //getAttentionByPerson
    Route::get('/history/{document}', [AttentionController::class, 'getAttentionByPerson']);

    //getTodayAttentions
    Route::get('/today', [AttentionController::class, 'getTodayAttentions']);

    //delete
    Route::delete('/{id}', [AttentionController::class, 'delete']);



    Route::get('/', [AttentionController::class, 'index']);
    Route::get('/last', [AttentionController::class, 'last']);

    Route::post('/{type}', [AttentionController::class, 'store']);

    Route::put('/', [AttentionController::class, 'update']);
    //report 
    Route::get('/report', [AttentionController::class, 'report']);
    //offices
    Route::get('/offices', [AttentionController::class, 'offices']);
    // Route::put('/{attention}', [AttentionController::class, 'update'])->middleware('can:attentions.update');
});


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'selects'], function () {
    Route::get('/offices', [OfficeController::class, 'forSelect']);
    Route::get('/roles', [RoleController::class, 'forSelect']);
    Route::get('/type-attentions', [TypeAttentionController::class, 'forSelect']);
    Route::get('/users', [UserController::class, 'forSelect']);
    // Route::get('/students', [StudentController::class, 'forSelect']);
    // Route::get('/workers', [WorkerController::class, 'forSelect']);
    // Route::get('/externals', [ExternalController::class, 'forSelect']);
});


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'reports'], function () {

    Route::get('/attentions-by-month/{year}', [ReportController::class, 'attentionsByMonth']);
    //attentionsByMonthType
    Route::get('/attentions-by-month-type/{year}', [ReportController::class, 'attentionsByMonthType']);
});



Route::post('/pdf/report',  [ReportController::class, 'rerportPdf']);
