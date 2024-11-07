<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

Route::middleware('auth:sanctum', 'verified' )->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('courses' , CourseController::class)->except(['index', 'show'])->middleware('auth:sanctum');
Route::get('courses', [CourseController::class, 'index']);
Route::get('courses/{course}', [CourseController::class, 'show']);



Route::post('/register' , [AuthController::class , 'register']);
Route::post('/login' , [AuthController::class , 'login']);
Route::post('/logout' , [AuthController::class , 'logout'])->middleware('auth:sanctum');

Route::apiResource('users' , UserController::class);

Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [AuthController::class, 'reset']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
        ->name('verification.verify');
    Route::post('/email/resend', [AuthController::class, 'resend'])
        ->name('verification.resend');
});