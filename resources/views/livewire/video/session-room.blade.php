<div class="flex flex-col h-screen bg-gray-900" x-data="videoRoom('{{ $session->room_id }}')" x-init="init()">
    {{-- Video grid --}}
    <div class="flex-1 grid grid-cols-2 gap-2 p-4" id="video-grid">
        <video id="local-video" autoplay muted playsinline class="w-full rounded-lg bg-black"></video>
    </div>

    {{-- Controls bar --}}
    <div class="flex items-center justify-center gap-6 py-4 bg-gray-800">
        <button @click="toggleMic()"
                :class="micOn ? 'bg-gray-600' : 'bg-red-600'"
                class="w-12 h-12 rounded-full flex items-center justify-center text-white hover:opacity-90 transition">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3zm-1 20.93V22h2v-.07A7.001 7.001 0 0019 15h-2a5 5 0 01-10 0H5a7.001 7.001 0 006 6.93z"/></svg>
        </button>
        <button @click="toggleCam()"
                :class="camOn ? 'bg-gray-600' : 'bg-red-600'"
                class="w-12 h-12 rounded-full flex items-center justify-center text-white hover:opacity-90 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
        </button>
        @if($session->contract_id)
            <button wire:click="toggleDocument"
                    class="w-12 h-12 rounded-full bg-gray-600 flex items-center justify-center text-white hover:bg-gray-500">
                📄
            </button>
        @endif
        <button wire:click="endSession"
                class="w-12 h-12 rounded-full bg-red-600 flex items-center justify-center text-white hover:bg-red-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z"/></svg>
        </button>
    </div>

    {{-- Overlays --}}
    @if($showDocument && $session->contract_id)
        <div class="absolute inset-0 bg-black/70 flex items-center justify-center z-20" wire:click.self="toggleDocument">
            <div class="bg-white rounded-xl shadow-2xl w-2/3 h-4/5 overflow-hidden flex flex-col">
                <livewire:video.in-call-document-viewer :sessionId="$session->id" :contractId="$session->contract_id" />
            </div>
        </div>
    @endif

    <livewire:video.participant-list :sessionId="$session->id" />
</div>

<script>
function videoRoom(roomId) {
    return {
        micOn: true, camOn: true, localStream: null,
        async init() {
            try {
                this.localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                document.getElementById('local-video').srcObject = this.localStream;
            } catch(e) { console.warn('Media access denied', e); }
        },
        toggleMic() { this.micOn = !this.micOn; this.localStream?.getAudioTracks().forEach(t => t.enabled = this.micOn); },
        toggleCam() { this.camOn = !this.camOn; this.localStream?.getVideoTracks().forEach(t => t.enabled = this.camOn); },
    }
}
</script>
