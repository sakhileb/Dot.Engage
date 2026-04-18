<div class="space-y-6">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <p class="text-sm font-medium text-gray-500">Total Contracts</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalContracts }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <p class="text-sm font-medium text-gray-500">Pending Signature</p>
            <p class="mt-1 text-3xl font-semibold text-yellow-600">{{ $pendingContracts }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
            <p class="text-sm font-medium text-gray-500">Active Video Sessions</p>
            <p class="mt-1 text-3xl font-semibold text-green-600">{{ $activeSessions->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Recent Contracts</h3>
                <a href="{{ route('contracts.index') }}" class="text-sm text-indigo-600 hover:underline">View all</a>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse($recentContracts as $contract)
                    <li class="px-4 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $contract->title }}</p>
                            <p class="text-xs text-gray-500">{{ $contract->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            {{ $contract->status === 'signed' ? 'bg-green-100 text-green-800' : ($contract->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($contract->status) }}
                        </span>
                    </li>
                @empty
                    <li class="px-4 py-3 text-sm text-gray-500">No contracts yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Recent Conversations</h3>
                <a href="{{ route('chat.index') }}" class="text-sm text-indigo-600 hover:underline">View all</a>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse($activeConversations as $conversation)
                    <li class="px-4 py-3">
                        <p class="text-sm font-medium text-gray-900">{{ $conversation->name ?? 'Direct Message' }}</p>
                        <p class="text-xs text-gray-500">{{ $conversation->last_message_at?->diffForHumans() ?? 'No messages yet' }}</p>
                    </li>
                @empty
                    <li class="px-4 py-3 text-sm text-gray-500">No conversations yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
