<x-app-layout>

<div style="padding:2rem 2.5rem 3rem;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;">
        <div>
            <h1 style="font-family:'Syne',sans-serif;font-size:1.5rem;font-weight:700;color:#f4f4f5;margin:0 0 0.2rem;letter-spacing:-0.01em;">Client Engagement</h1>
            <p style="font-size:0.78rem;color:#52525b;margin:0;">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <a href="{{ route('sessions.create') }}" class="dot-btn dot-btn-primary">
            <span class="material-symbols-rounded" style="font-size:15px;">add</span>
            New Session
        </a>
    </div>

    {{-- KPI Strip --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem;">
        @php
            $team = auth()->user()->currentTeam;
            $stats = [
                ['label' => 'Active Clients',     'val' => $team ? $team->clients()->where('status','active')->count() : 0,   'color' => 'var(--accent)'],
                ['label' => 'Sessions This Month', 'val' => $team ? $team->sessions()->whereMonth('scheduled_at', now()->month)->count() : 0, 'color' => '#10b981'],
                ['label' => 'Signed Contracts',   'val' => $team ? $team->contracts()->where('status','signed')->count() : 0, 'color' => '#f59e0b'],
                ['label' => 'Proposals Sent',     'val' => $team ? $team->proposals()->whereMonth('created_at', now()->month)->count() : 0, 'color' => '#a855f7'],
            ];
        @endphp
        @foreach($stats as $stat)
        <div class="dot-card" style="padding:1.25rem 1.5rem;">
            <div style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.09em;color:#52525b;margin-bottom:0.75rem;">{{ $stat['label'] }}</div>
            <div class="metric-val" style="font-size:2rem;font-weight:600;color:{{ $stat['color'] }};">{{ $stat['val'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Two columns: upcoming sessions + recent clients --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

        {{-- Upcoming Sessions --}}
        <div class="dot-card" style="padding:1.5rem;">
            <h3 style="font-family:'Syne',sans-serif;font-size:0.875rem;font-weight:700;color:#f4f4f5;margin:0 0 1.25rem;">Upcoming Sessions</h3>
            @php
                $upcoming = $team ? $team->sessions()->where('scheduled_at','>=',now())->orderBy('scheduled_at')->limit(5)->with('client')->get() : collect();
            @endphp
            @if($upcoming->isEmpty())
            <div style="text-align:center;padding:2rem 0;">
                <span class="material-symbols-rounded" style="font-size:32px;color:#3f3f46;display:block;margin-bottom:0.75rem;">event_note</span>
                <p style="font-size:0.8rem;color:#52525b;margin:0;">No upcoming sessions scheduled</p>
            </div>
            @else
            @foreach($upcoming as $session)
            <div style="display:flex;align-items:center;gap:0.75rem;padding:0.65rem 0;border-bottom:1px solid rgba(255,255,255,0.05);">
                <div style="width:32px;height:32px;border-radius:8px;background:rgba(var(--accent-rgb),0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span class="material-symbols-rounded" style="font-size:15px;color:var(--accent);">videocam</span>
                </div>
                <div style="min-width:0;flex:1;">
                    <div style="font-size:12px;font-weight:600;color:#d4d4d8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $session->client->name ?? 'Client' }}</div>
                    <div style="font-size:11px;color:#52525b;">{{ $session->scheduled_at->format('M d · H:i') }}</div>
                </div>
            </div>
            @endforeach
            @endif
        </div>

        {{-- Recent Clients --}}
        <div class="dot-card" style="padding:1.5rem;">
            <h3 style="font-family:'Syne',sans-serif;font-size:0.875rem;font-weight:700;color:#f4f4f5;margin:0 0 1.25rem;">Recent Clients</h3>
            @php
                $clients = $team ? $team->clients()->orderBy('created_at','desc')->limit(5)->get() : collect();
            @endphp
            @if($clients->isEmpty())
            <div style="text-align:center;padding:2rem 0;">
                <span class="material-symbols-rounded" style="font-size:32px;color:#3f3f46;display:block;margin-bottom:0.75rem;">group</span>
                <p style="font-size:0.8rem;color:#52525b;margin:0;">No clients added yet. Add your first client to get started.</p>
            </div>
            @else
            @foreach($clients as $client)
            <div style="display:flex;align-items:center;gap:0.75rem;padding:0.65rem 0;border-bottom:1px solid rgba(255,255,255,0.05);">
                <div style="width:28px;height:28px;border-radius:50%;background:rgba(var(--accent-rgb),0.15);border:1px solid rgba(var(--accent-rgb),0.25);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:var(--accent);font-family:'Syne',sans-serif;flex-shrink:0;">{{ strtoupper(substr($client->name,0,1)) }}</div>
                <div style="min-width:0;flex:1;">
                    <div style="font-size:12px;font-weight:600;color:#d4d4d8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $client->name }}</div>
                    <div style="font-size:11px;color:#52525b;">{{ ucfirst($client->status ?? 'active') }}</div>
                </div>
            </div>
            @endforeach
            @endif
        </div>

    </div>

</div>

</x-app-layout>
