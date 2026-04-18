<div class="absolute top-4 right-4 bg-gray-800/90 rounded-lg p-3 w-48 space-y-2" wire:poll.5s="refresh">
    <h4 class="text-xs font-semibold text-gray-400 uppercase">Participants ({{ $participants->count() }})</h4>
    <ul class="space-y-1.5">
        @foreach($participants as $participant)
            <li class="flex items-center gap-2">
                <img src="{{ $participant->profile_photo_url }}" class="w-6 h-6 rounded-full" alt="">
                <span class="text-xs text-white truncate">{{ $participant->name }}</span>
                @if($participant->id === auth()->id())
                    <span class="text-xs text-gray-400">(you)</span>
                @endif
            </li>
        @endforeach
    </ul>
</div>
