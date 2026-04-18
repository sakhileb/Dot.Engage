<?php

namespace App\Livewire\Video;

use App\Models\VideoSession;
use Livewire\Component;

class ParticipantList extends Component
{
    public int $sessionId;

    public function refresh(): void {}

    public function render()
    {
        // Participants = all team members of the session's team who have not left
        $session      = VideoSession::with('team.users')->findOrFail($this->sessionId);
        $participants = $session->team->allUsers();

        return view('livewire.video.participant-list', compact('participants'));
    }
}
