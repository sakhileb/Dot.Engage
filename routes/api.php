<?php

use App\Http\Controllers\Api\ContractPdfController;
use App\Http\Controllers\Api\SignatureController;
use App\Http\Controllers\Api\VideoTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes here are stateless and authenticated via Laravel Sanctum.
| Livewire forms handle most mutations directly; these endpoints serve
| base64 signature uploads, private PDF streaming, and video room tokens.
|
*/

Route::middleware('auth:sanctum')->group(function () {

    // Authenticated user info (default Sanctum route).
    Route::get('/user', fn (Request $request) => $request->user());

    // ── Signatures ──────────────────────────────────────────────────────────
    // POST  /api/signatures   — decode + persist a base64 canvas signature
    Route::post('/signatures', SignatureController::class)
        ->name('api.signatures.store');

    // ── Contract PDF streaming ───────────────────────────────────────────────
    // GET   /api/contracts/{contract}/pdf   — stream private PDF inline
    Route::get('/contracts/{contract}/pdf', ContractPdfController::class)
        ->name('api.contracts.pdf');

    // ── Video room token ─────────────────────────────────────────────────────
    // POST  /api/video/token   — return Reverb credentials for a room
    Route::post('/video/token', VideoTokenController::class)
        ->name('api.video.token');
});
