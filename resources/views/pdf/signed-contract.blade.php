<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Signed Contract — {{ $contract->title }}</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #1a1a1a; background: #fff; }
    .page { padding: 48px; }

    /* Header */
    .header { border-bottom: 3px solid #4f46e5; padding-bottom: 20px; margin-bottom: 28px; }
    .header-top { display: flex; justify-content: space-between; align-items: flex-start; }
    .brand { font-size: 22px; font-weight: 700; color: #4f46e5; letter-spacing: -0.5px; }
    .brand span { color: #1a1a1a; }
    .badge { background: #dcfce7; color: #166534; font-size: 10px; font-weight: 700;
             padding: 4px 10px; border-radius: 20px; letter-spacing: 0.5px; text-transform: uppercase; }
    .contract-title { margin-top: 16px; font-size: 18px; font-weight: 700; color: #111; }
    .contract-meta { margin-top: 6px; font-size: 11px; color: #6b7280; }

    /* Section */
    .section { margin-bottom: 24px; }
    .section-label { font-size: 9px; font-weight: 700; color: #6b7280; text-transform: uppercase;
                     letter-spacing: 1px; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; }

    /* Details grid */
    .details-grid { display: table; width: 100%; }
    .detail-row { display: table-row; }
    .detail-key { display: table-cell; width: 140px; font-size: 11px; color: #6b7280; padding: 4px 0; }
    .detail-val { display: table-cell; font-size: 11px; color: #111; font-weight: 500; padding: 4px 0; }

    /* Description box */
    .description-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px;
                        padding: 12px; font-size: 11px; color: #374151; line-height: 1.6; }

    /* Signature block */
    .sig-grid { display: table; width: 100%; border-collapse: collapse; }
    .sig-row { display: table-row; }
    .sig-cell { display: table-cell; width: 50%; vertical-align: top; padding: 12px 16px 12px 0; }
    .sig-cell:last-child { padding-right: 0; padding-left: 16px; border-left: 1px solid #e5e7eb; }
    .sig-box { border: 1px solid #d1d5db; border-radius: 8px; padding: 12px; background: #fafafa; }
    .sig-name { font-size: 12px; font-weight: 700; color: #111; margin-bottom: 2px; }
    .sig-meta { font-size: 10px; color: #6b7280; margin-bottom: 10px; }
    .sig-image { max-width: 200px; max-height: 60px; border-bottom: 1px solid #c7d2fe; padding-bottom: 4px; }
    .sig-placeholder { width: 200px; height: 50px; border-bottom: 1px solid #c7d2fe;
                        display: flex; align-items: flex-end; padding-bottom: 4px;
                        color: #9ca3af; font-size: 10px; font-style: italic; }

    /* Verification block */
    .verification { background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px;
                     padding: 14px; margin-top: 24px; }
    .verification-title { font-size: 10px; font-weight: 700; color: #0369a1; text-transform: uppercase;
                           letter-spacing: 0.5px; margin-bottom: 8px; }
    .verification-grid { display: table; width: 100%; }
    .vrow { display: table-row; }
    .vkey { display: table-cell; width: 180px; font-size: 10px; color: #0369a1; padding: 2px 0; }
    .vval { display: table-cell; font-size: 10px; color: #0c4a6e; font-family: monospace; padding: 2px 0; }

    /* Footer */
    .footer { margin-top: 32px; padding-top: 14px; border-top: 1px solid #e5e7eb;
               font-size: 9px; color: #9ca3af; text-align: center; line-height: 1.6; }
</style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="header-top">
            <div class="brand">Dot<span>.Engage</span></div>
            <div class="badge">✓ Fully Signed</div>
        </div>
        <div class="contract-title">{{ $contract->title }}</div>
        <div class="contract-meta">
            Team: {{ $contract->team->name }} &nbsp;·&nbsp;
            Document ID: {{ $contract->id }} &nbsp;·&nbsp;
            Signed: {{ $signedAt->format('d F Y, H:i') }} UTC
        </div>
    </div>

    {{-- Contract details --}}
    <div class="section">
        <div class="section-label">Contract Details</div>
        <div class="details-grid">
            <div class="detail-row">
                <div class="detail-key">Created by</div>
                <div class="detail-val">{{ $contract->creator->name }} ({{ $contract->creator->email }})</div>
            </div>
            <div class="detail-row">
                <div class="detail-key">Created on</div>
                <div class="detail-val">{{ $contract->created_at->format('d F Y') }}</div>
            </div>
            @if($contract->expires_at)
            <div class="detail-row">
                <div class="detail-key">Expiry date</div>
                <div class="detail-val">{{ $contract->expires_at->format('d F Y') }}</div>
            </div>
            @endif
            <div class="detail-row">
                <div class="detail-key">Status</div>
                <div class="detail-val">Fully executed</div>
            </div>
        </div>
    </div>

    @if($contract->description)
    <div class="section">
        <div class="section-label">Description</div>
        <div class="description-box">{{ $contract->description }}</div>
    </div>
    @endif

    {{-- Signatures --}}
    <div class="section">
        <div class="section-label">Electronic Signatures</div>
        <div class="sig-grid">
            @foreach($signatures->chunk(2) as $pair)
            <div class="sig-row">
                @foreach($pair as $sig)
                <div class="sig-cell">
                    <div class="sig-box">
                        <div class="sig-name">{{ $sig['user']['name'] ?? 'Unknown' }}</div>
                        <div class="sig-meta">
                            {{ $sig['user']['email'] ?? '' }}<br>
                            Signed {{ \Carbon\Carbon::parse($sig['signed_at'])->format('d F Y, H:i') }} UTC
                            @if($sig['ip_address'])
                                &nbsp;· IP {{ $sig['ip_address'] }}
                            @endif
                        </div>
                        @if($sig['image_data_uri'])
                            <img src="{{ $sig['image_data_uri'] }}" class="sig-image" alt="Signature">
                        @else
                            <div class="sig-placeholder">Signature on file</div>
                        @endif
                    </div>
                </div>
                @endforeach
                {{-- Pad with empty cell if odd number of signatures --}}
                @if($pair->count() === 1)
                <div class="sig-cell"></div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Verification --}}
    <div class="verification">
        <div class="verification-title">Document Verification</div>
        <div class="verification-grid">
            <div class="vrow">
                <div class="vkey">Document hash (SHA-256)</div>
                <div class="vval">{{ hash('sha256', $contract->id . $contract->title . $signedAt->toIso8601String()) }}</div>
            </div>
            <div class="vrow">
                <div class="vkey">Parties</div>
                <div class="vval">{{ $signatures->count() }} signator{{ $signatures->count() === 1 ? 'y' : 'ies' }}</div>
            </div>
            <div class="vrow">
                <div class="vkey">Platform</div>
                <div class="vval">Dot.Engage — BluPin Incorporated / SK Digital</div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        This document was electronically signed via Dot.Engage. Each signature was captured with the signer's
        IP address, timestamp, and user agent as evidence of intent. This certificate constitutes a legally
        binding electronic signature under applicable e-signature laws.
    </div>

</div>
</body>
</html>
