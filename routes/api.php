<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

use App\Http\Controllers\Api\PageController;

Route::apiResource('pages', PageController::class);

Route::get('/slug-pages/{slug}', [PageController::class, 'show_slug'])->where('slug', '.*');
