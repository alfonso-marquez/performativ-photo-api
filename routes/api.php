<?php

use App\Http\Controllers\Api\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// added prefix
Route::prefix("photos")->group(function () {
    Route::get('/', [PhotoController::class, 'index']);       // GET /api/photos
    Route::post('/', [PhotoController::class, 'store']);      // POST /api/photos
    Route::get('{id}', [PhotoController::class, 'show']);     // GET /api/photos/{id}
    Route::put('{id}', [PhotoController::class, 'update']);   // PUT /api/photos/{id}
    Route::delete('{id}', [PhotoController::class, 'destroy']); // DELETE /api/photos/{id}
});
