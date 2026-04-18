<?php

namespace App\Livewire\Dashboard;

use App\Models\Contract;
use App\Models\Conversation;
use App\Models\VideoSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TeamDashboard extends Component
{
    public function render()
    {
        $team = Auth::user()->currentTeam;

        return view('livewire.dashboard.team-dashboard', [
            'totalContracts'      => Contract::where('team_id', $team->id)->count(),
            'pendingContracts'    => Contract::where('team_id', $team->id)->where('status', 'pending')->count(),
            'recentContracts'     => Contract::where('team_id', $team->id)->latest()->limit(5)->get(),
            'activeConversations' => Conversation::where('team_id', $team->id)->latest('last_message_at')->limit(5)->get(),
            'activeSessions'      => VideoSession::where('team_id', $team->id)->where('status', 'active')->limit(5)->get(),
        ]);
    }
}
