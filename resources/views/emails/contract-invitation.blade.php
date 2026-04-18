<x-mail::message>
# You've been invited to review a contract

Hi {{ $recipient->name }},

**{{ $invitedBy->name }}** has shared the following contract with you on **{{ config('app.name') }}**:

<x-mail::panel>
**{{ $contract->title }}**

@if($contract->description)
{{ $contract->description }}
@endif

Status: {{ ucfirst($contract->status) }}
</x-mail::panel>

Please review the document at your earliest convenience. You may be required to sign it before it becomes active.

<x-mail::button :url="$reviewUrl" color="green">
Review Contract
</x-mail::button>

@if($contract->expires_at)
> **Note:** This contract expires on {{ $contract->expires_at->format('d M Y') }}.
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

