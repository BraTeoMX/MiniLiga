<?php

use App\Http\Controllers\TeamController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\StandingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('teams', TeamController::class)->only(['index', 'store']);
Route::post('matches/{id}/result', [MatchController::class, 'storeResult']);
Route::get('standings', [StandingsController::class, 'index']);
