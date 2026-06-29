<?php

use App\Http\Controllers\Auth\EcosystemAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/ecosystem', [EcosystemAuthController::class, 'handle'])
    ->name('ecosystem.auth');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // ── Dashboard ───────────────────────────────────────────────────────────
    Route::get('/dashboard', function () {
        $stats = [
            'total_contracts'       => \App\Models\Contract::count(),
            'pending_signatures'    => \App\Models\Contract::where('status', 'pending')->count(),
            'signed_contracts'      => \App\Models\Contract::where('status', 'signed')->count(),
            'active_conversations'  => \App\Models\Conversation::whereNotNull('last_message_at')->count(),
            'active_video_sessions' => \App\Models\VideoSession::whereIn('status', ['waiting', 'active'])->count(),
        ];

        $recentContracts = \App\Models\Contract::with('creator')
            ->latest()
            ->limit(5)
            ->get();

        $activeConversations = \App\Models\Conversation::withCount('participants')
            ->whereNotNull('last_message_at')
            ->orderByDesc('last_message_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentContracts', 'activeConversations'));
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
