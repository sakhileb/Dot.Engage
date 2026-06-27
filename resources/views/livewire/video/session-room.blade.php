<div class="flex flex-col h-screen bg-gray-900"
     x-data="videoRoom('{{ $session->id }}')"
     x-init="init()">

    {{-- Video area --}}
    <div class="flex-1 relative overflow-hidden">
        {{-- Daily.co call frame fills this div --}}
        <div id="daily-frame" class="w-full h-full"></div>

        {{-- Fallback: shown when Daily.co is not configured --}}
        <div id="local-video-fallback" class="hidden w-full h-full flex items-center justify-center bg-gray-900">
            <video id="local-video" autoplay muted playsinline
                   class="w-full h-full object-cover"></video>
            <div class="absolute top-4 left-4 bg-black/60 text-white px-4 py-3 rounded-lg max-w-xs">
                <p class="text-xs font-semibold text-yellow-300 mb-1">Video calling not configured</p>
                <p class="text-xs text-gray-300">Set <code class="text-yellow-200">DAILY_API_KEY</code> to enable peer-to-peer video.</p>
                <p class="text-xs text-gray-400 mt-1 break-all">Room: {{ $session->room_id }}</p>
            </div>
        </div>

        {{-- Participant list overlay --}}
        <livewire:video.participant-list :sessionId="$session->id" />
    </div>

    {{-- Controls bar --}}
    <div class="flex items-center justify-center gap-4 py-4 px-6 bg-gray-800 flex-shrink-0">
        <button @click="toggleMic()"
                :class="micOn ? 'bg-gray-600 hover:bg-gray-500' : 'bg-red-600 hover:bg-red-700'"
                class="w-12 h-12 rounded-full flex items-center justify-center text-white transition"
                :title="micOn ? 'Mute' : 'Unmute'">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path x-show="micOn" d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3zm-1 20.93V22h2v-.07A7.001 7.001 0 0019 15h-2a5 5 0 01-10 0H5a7.001 7.001 0 006 6.93z"/>
                <path x-show="!micOn" d="M19 11h-1.7c0 .74-.16 1.43-.43 2.05l1.23 1.23c.56-.98.9-2.09.9-3.28zm-4.02.17c0-.06.02-.11.02-.17V5c0-1.66-1.34-3-3-3S9 3.34 9 5v.18l5.98 5.99zM4.27 3L3 4.27l6.01 6.01V11c0 1.66 1.33 3 2.99 3 .22 0 .44-.03.65-.08l1.66 1.66c-.71.33-1.5.52-2.31.52-2.76 0-5.3-2.1-5.3-5.1H5c0 3.41 2.72 6.23 6 6.72V21h2v-3.28c.91-.13 1.77-.45 2.54-.9L19.73 21 21 19.73 4.27 3z"/>
            </svg>
        </button>

        <button @click="toggleCam()"
                :class="camOn ? 'bg-gray-600 hover:bg-gray-500' : 'bg-red-600 hover:bg-red-700'"
                class="w-12 h-12 rounded-full flex items-center justify-center text-white transition"
                :title="camOn ? 'Stop camera' : 'Start camera'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="camOn" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                <path x-show="!camOn" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
        </button>

        @if($session->contract_id)
            <button wire:click="toggleDocument"
                    class="w-12 h-12 rounded-full bg-indigo-600 hover:bg-indigo-700 flex items-center justify-center text-white transition"
                    title="Toggle contract">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </button>
        @endif

        <button @click="leaveAndEnd()"
                class="w-12 h-12 rounded-full bg-red-600 hover:bg-red-700 flex items-center justify-center text-white transition"
                title="End call">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z"/>
            </svg>
        </button>
    </div>

    {{-- In-call contract overlay --}}
    @if($showDocument && $session->contract_id)
        <div class="absolute inset-0 bg-black/75 flex items-center justify-center z-20"
             wire:click.self="toggleDocument">
            <div class="bg-white rounded-xl shadow-2xl w-2/3 h-4/5 overflow-hidden flex flex-col">
                <livewire:video.in-call-document-viewer
                    :sessionId="$session->id"
                    :contractId="$session->contract_id" />
            </div>
        </div>
    @endif
</div>

<script>
function videoRoom(sessionId) {
    return {
        micOn: true,
        camOn: true,
        callFrame: null,
        localStream: null,

        async init() {
            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            let joinUrl = null;
            try {
                const res = await fetch(`/api/video/${sessionId}/join-url`, {
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    credentials: 'same-origin',
                });
                const data = await res.json();
                if (data.configured) joinUrl = data.url;
            } catch (e) {
                console.warn('Could not fetch Daily.co join URL', e);
            }

            if (joinUrl) {
                // Daily.co path: embed an iframe call frame.
                const { default: DailyIframe } = await import('@daily-co/daily-js');
                this.callFrame = DailyIframe.createFrame(
                    document.getElementById('daily-frame'),
                    {
                        showLeaveButton: false,
                        showFullscreenButton: true,
                        iframeStyle: { width: '100%', height: '100%', border: 'none' },
                    }
                );
                await this.callFrame.join({ url: joinUrl });
            } else {
                // Fallback path: local camera preview only.
                document.getElementById('daily-frame').classList.add('hidden');
                document.getElementById('local-video-fallback').classList.remove('hidden');
                try {
                    this.localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                    document.getElementById('local-video').srcObject = this.localStream;
                } catch (e) {
                    console.warn('Media access denied', e);
                }
            }
        },

        toggleMic() {
            this.micOn = !this.micOn;
            if (this.callFrame) {
                this.callFrame.setLocalAudio(this.micOn);
            } else {
                this.localStream?.getAudioTracks().forEach(t => t.enabled = this.micOn);
            }
        },

        toggleCam() {
            this.camOn = !this.camOn;
            if (this.callFrame) {
                this.callFrame.setLocalVideo(this.camOn);
            } else {
                this.localStream?.getVideoTracks().forEach(t => t.enabled = this.camOn);
            }
        },

        async leaveAndEnd() {
            if (this.callFrame) {
                await this.callFrame.leave();
                this.callFrame.destroy();
            } else {
                this.localStream?.getTracks().forEach(t => t.stop());
            }
            // Delegate to Livewire to mark session ended in DB.
            @this.call('endSession');
        },
    }
}
</script>
