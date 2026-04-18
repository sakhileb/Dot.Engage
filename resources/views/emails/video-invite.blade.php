<x-mail::message>
# You've been invited to a video session

Hi {{ $recipient->name }},

**{{ $initiator->name }}** has started a live video session on **{{ config('app.name') }}** and would like you to join.

@if($contract)
<x-mail::panel>
**Document signing required**

The following contract is attached to this session and will need your signature:\
**{{ $contract->title }}**
</x-mail::panel>
@endif

The session is live right now. Click the button below to join:

<x-mail::button :url="$joinUrl" color="green">
Join Video Session
</x-mail::button>

<x-mail::table>
| Detail | Value |
|:---|:---|
| Initiated by | {{ $initiator->name }} |
| Room ID | {{ $session->room_id }} |
| Started | {{ $session->started_at ? $session->started_at->format('d M Y, H:i') : 'Just now' }} |
@if($contract)
| Contract | {{ $contract->title }} |
@endif
</x-mail::table>

If you are unable to join now, please contact **{{ $initiator->name }}** directly.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

