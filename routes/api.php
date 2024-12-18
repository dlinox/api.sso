<?php

use App\Http\Controllers\AttentionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ExternalController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatisfactionSurveyController;
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
    Route::post('/', [ProfessorController::class, 'store'])->middleware('can:professors.create');
    Route::put('/{professor}', [ProfessorController::class, 'update'])->middleware('can:professors.update');
    Route::get('/search/{term}', [ProfessorController::class, 'search']);
    Route::get('/by-document/{document}', [ProfessorController::class, 'getByDocument']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'students'], function () {
    Route::post('/items', [StudentController::class, 'items']);
    Route::get('/', [StudentController::class, 'index'])->middleware('can:students');
    Route::post('/', [StudentController::class, 'store'])->middleware('can:students.create');
    Route::put('/{student}', [StudentController::class, 'update'])->middleware('can:students.update');
    Route::post('/receive/{document}', [StudentController::class, 'receiveStudent']);
    Route::get('/by-document/{document}', [StudentController::class, 'getByDocument']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'workers'], function () {
    Route::post('/items', [WorkerController::class, 'items']);
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
    Route::post('/items', [UserController::class, 'items']);
    Route::post('/', [UserController::class, 'store'])->middleware('can:users.create');
    Route::put('/{user}', [UserController::class, 'update'])->middleware('can:users.update');
    Route::get('/offices', [UserController::class, 'offices']);
    Route::get('/roles', [UserController::class, 'roles']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'roles'], function () {
    Route::post('/items', [RoleController::class, 'items']);
    Route::post('/', [RoleController::class, 'store'])->middleware('can:roles.create');
    Route::put('/{role}', [RoleController::class, 'update'])->middleware('can:roles.update');
    Route::post('/{role}/permissions', [RoleController::class, 'assignPermissions'])->middleware('can:roles.assign-permissions');
    Route::get('/permissions', [RoleController::class, 'permissions'])->middleware('can:roles');
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'attentions'], function () {
    Route::post('/items-students/{type}', [AttentionController::class, 'itemsStudents']);
    Route::post('/student/receive/{document}', [StudentController::class, 'receiveStudent']);
    Route::get('/student/by-document/{document}', [StudentController::class, 'getByDocument']);
    Route::get('/professor/search/{term}', [ProfessorController::class, 'search']);
    Route::get('/professor/by-document/{document}', [ProfessorController::class, 'getByDocument']);
    Route::get('/worker/search/{term}', [WorkerController::class, 'search']);
    Route::get('/worker/by-document/{document}', [WorkerController::class, 'getByDocument']);
    Route::get('/history/{document}', [AttentionController::class, 'getAttentionByPerson']);
    Route::get('/today', [AttentionController::class, 'getTodayAttentions']);
    Route::delete('/{id}', [AttentionController::class, 'delete']);
    Route::post('/{type}', [AttentionController::class, 'store']);
    Route::put('/', [AttentionController::class, 'update']);
    Route::get('/report', [AttentionController::class, 'report']);
    Route::get('/offices', [AttentionController::class, 'offices']);
    Route::get('/student/by-code/{code}', [StudentController::class, 'getStudentByCode']);
    //getNextByType
    Route::get("next-num-by-type/{typeId}", [AttentionController::class, 'getNextByType']);
});

Route::group(['prefix' => 'survey'], function () {
    Route::post('/send-email', [SatisfactionSurveyController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/{token}', [SatisfactionSurveyController::class, 'getSurvey']);
    Route::put('/response/{token}', [SatisfactionSurveyController::class, 'responseSurvey']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'reports'], function () {
    Route::get('/items/users', [ReportController::class, 'ratingForUser']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'selects'], function () {
    Route::get('/offices', [OfficeController::class, 'forSelect']);
    Route::get('/roles', [RoleController::class, 'forSelect']);
    Route::get('/type-attentions', [TypeAttentionController::class, 'forSelect']);
    Route::get('/users', [UserController::class, 'forSelect']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'reports'], function () {
    Route::get('/attentions-by-month/{year}', [ReportController::class, 'attentionsByMonth']);
    Route::get('/attentions-by-month-type/{year}', [ReportController::class, 'attentionsByMonthType']);
});

Route::post('/pdf/report',  [ReportController::class, 'rerportPdf'])->middleware('auth:sanctum');


Route::post('/pdf/report/users',  [ReportController::class, 'rerportUserPdf'])->middleware('auth:sanctum');