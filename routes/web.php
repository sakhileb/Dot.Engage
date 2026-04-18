<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // ── Dashboard ───────────────────────────────────────────────────────────
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ── Contracts ───────────────────────────────────────────────────────────
    // Livewire components handle all mutations; these are page-view routes only.
    Route::get('/contracts', fn () => view('contracts.index'))
        ->name('contracts.index');

    Route::get('/contracts/create', fn () => view('contracts.create'))
        ->name('contracts.create');

    Route::get('/contracts/{contract}', fn (\App\Models\Contract $contract) => view('contracts.show', ['contractId' => $contract->id]))
        ->name('contracts.show');

    Route::get('/contracts/{contract}/edit', fn (\App\Models\Contract $contract) => view('contracts.edit', ['contractId' => $contract->id]))
        ->name('contracts.edit');

    // ── Chat ─────────────────────────────────────────────────────────────────
    Route::get('/chat', fn () => view('chat.index'))
        ->name('chat.index');

    Route::get('/chat/{conversation}', fn (\App\Models\Conversation $conversation) => view('chat.show', ['conversationId' => $conversation->id]))
        ->name('chat.show');

    // ── Video Sessions ────────────────────────────────────────────────────────
    // {room} matches room_id (UUID string) — NOT the numeric primary key.
    Route::get('/video', fn () => view('video.index'))
        ->name('video.index');

    Route::get('/video/{room}', function (string $room) {
        $session = \App\Models\VideoSession::where('room_id', $room)->firstOrFail();
        return view('video.room', ['sessionId' => $session->id]);
    })->name('video.room');

    // ── Notifications ─────────────────────────────────────────────────────────
    Route::get('/notifications', fn () => view('notifications.index'))
        ->name('notifications.index');
});
