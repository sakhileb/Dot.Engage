<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Video Session</h2>
    </x-slot>
    <div class="py-0" style="height:calc(100vh - 64px)">
        <livewire:video.session-room :session-id="$sessionId" />
    </div>
</x-app-layout>