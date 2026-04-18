<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-4 py-4 border-b border-gray-200">
        <h3 class="text-sm font-semibold text-gray-700">Version History</h3>
    </div>
    <ul class="divide-y divide-gray-200">
        @forelse($versions as $version)
            <li class="px-4 py-3 flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-gray-900">v{{ $version->version_number }}</span>
                    <p class="text-xs text-gray-500">{{ $version->creator->name }} &middot; {{ $version->created_at->format('M d, Y H:i') }}</p>
                    @if($version->change_notes)
                        <p class="text-xs text-gray-600 mt-0.5">{{ $version->change_notes }}</p>
                    @endif
                </div>
                <a href="{{ Storage::url($version->file_path) }}" target="_blank"
                   class="text-xs text-indigo-600 hover:underline">Download</a>
            </li>
        @empty
            <li class="px-4 py-3 text-sm text-gray-500">No version history yet.</li>
        @endforelse
    </ul>
</div>
