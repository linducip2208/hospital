@php
    $brand = \App\Models\Setting::where('group', 'branding')->pluck('value', 'key');
    $hospitalName = $brand['branding_app_name'] ?? config('app.name', 'Hospital SIRS');
    $logoUrl = $brand['branding_logo_url'] ?? null;
    $address = $brand['branding_address'] ?? '';
    $phone = $brand['branding_phone'] ?? '';
    $email = $brand['branding_email'] ?? '';
    $website = $brand['branding_website'] ?? '';
    $licenseNo = $brand['branding_license_no'] ?? '';
@endphp
<table style="width:100%; border-bottom:3px double #000; padding-bottom:8px; margin-bottom:14px;">
    <tr>
        @if($logoUrl)
        <td style="width:80px; vertical-align:middle;">
            <img src="{{ $logoUrl }}" alt="logo" style="max-width:70px; max-height:70px;">
        </td>
        @endif
        <td style="vertical-align:middle; text-align:center;">
            <div style="font-size:20px; font-weight:bold; letter-spacing:0.5px;">{{ strtoupper($hospitalName) }}</div>
            @if($address)<div style="font-size:11px;">{{ $address }}</div>@endif
            <div style="font-size:11px;">
                @if($phone) Telp: {{ $phone }} @endif
                @if($email) | Email: {{ $email }} @endif
                @if($website) | {{ $website }} @endif
            </div>
            @if($licenseNo)<div style="font-size:10px;">Izin Operasional: {{ $licenseNo }}</div>@endif
        </td>
    </tr>
</table>
