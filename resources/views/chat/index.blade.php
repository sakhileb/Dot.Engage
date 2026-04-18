<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Messages</h2></x-slot>
    <div class="py-0 flex" style="height:calc(100vh - 64px)">
        <div class="w-72 border-r border-gray-200 bg-white flex-shrink-0">
            <livewire:chat.conversation-list />
            <livewire:chat.new-conversation />
        </div>
        <div class="flex-1">
            @isset($conversationId)
                <livewire:chat.conversation-thread :conversation-id="$conversationId" />
            @else
                <div class="flex items-center justify-center h-full text-gray-400">Select a conversation to start messaging.</div>
            @endisset
        </div>
    </div>
</x-app-layout>