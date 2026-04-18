<x-mail::message>
# Contract Signed &amp; Ready

Hi {{ $recipient->name }},

All parties have signed the contract and it is now complete.

<x-mail::panel>
**{{ $contract->title }}**

Version: {{ $signedVersion->version_number }}\
Signed on: {{ $signedVersion->created_at->format('d M Y') }}
</x-mail::panel>

The fully signed copy is attached to this email as a PDF. You can also view it at any time in {{ config('app.name') }}.

<x-mail::button :url="$viewUrl" color="green">
View Signed Contract
</x-mail::button>

<x-mail::table>
| Detail | Value |
|:---|:---|
| Contract | {{ $contract->title }} |
| Status | {{ ucfirst($contract->status) }} |
| Completed | {{ $signedVersion->created_at->format('d M Y, H:i') }} |
</x-mail::table>

Please retain this email and the attached PDF for your records.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

