<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\GameMatchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Tournament Routes
Route::get('/tournaments', [TournamentController::class, 'index']);

Route::post('/tournaments',[TournamentController::class, 'store']);

Route::get('/tournaments/{id}', [TournamentController::class, 'show']);

Route::put('/tournaments/{id}', [TournamentController::class, 'update']);

Route::delete('/tournaments/{id}', [TournamentController::class, 'delete']);

//Team Routes
Route::get('/teams', [TeamController::class, 'index']);

Route::post('/teams', [TeamController::class, 'store']);

Route::get('/teams/{id}', [TeamController::class, 'show']);

Route::put('/teams/{id}', [TeamController::class, 'update']);

Route::delete('/teams/{id}', [TeamController::class, 'delete']);

//Registration Routes
Route::get('/registrations', [RegistrationController::class, 'index']);

Route::post('/registrations', [RegistrationController::class, 'store']);

Route::get('/registrations/{id}', [RegistrationController::class, 'show']);

Route::put('/registrations/{id}', [RegistrationController::class, 'update']);

Route::delete('/registrations/{id}', [RegistrationController::class, 'delete']);

//Match Routes
Route::get('/matches', [GameMatchController::class, 'index']);

Route::post('/matches', [GameMatchController::class, 'store']);

Route::get('/matches/{id}', [GameMatchController::class, 'show']);

Route::put('/matches/{id}', [GameMatchController::class, 'update']);

Route::delete('/matches/{id}', [GameMatchController::class, 'delete']);


