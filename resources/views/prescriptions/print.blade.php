@extends('layouts.print')
@section('title', 'Resep '.$prescription->rx_no)
@section('content')
<table style="width:100%; margin-bottom:10px;">
    <tr>
        <td>Dokter: <strong>{{ $prescription->doctor->name ?? '-' }}</strong></td>
        <td class="text-end">{{ $prescription->prescribed_at?->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
        <td>SIP/STR: {{ $prescription->doctor->str_number ?? '-' }}</td>
        <td class="text-end">No: {{ $prescription->rx_no }}</td>
    </tr>
</table>

<table style="width:100%; border-bottom:1px solid #000; padding-bottom:6px; margin-bottom:14px;">
    <tr>
        <td>Nama Pasien : <strong>{{ $prescription->patient->name ?? '-' }}</strong></td>
        <td>Umur: {{ $prescription->patient?->birth_date ? \Carbon\Carbon::parse($prescription->patient->birth_date)->age.' th' : '-' }}</td>
        <td>BB: -</td>
    </tr>
</table>

<div style="font-family: 'Courier New', monospace; font-size:13pt; line-height:1.7;">
    @foreach($prescription->items as $it)
    <div style="margin-bottom:10px; padding-left:20px; position:relative;">
        <div style="position:absolute; left:0; font-size:18pt; font-weight:bold;">℞</div>
        <strong>{{ $it->drug_name }}</strong>
        @if($it->dose) {{ $it->dose }}@endif
        @if($it->is_compounded) <em>(racikan)</em>@endif
        @if($it->is_high_alert) <span style="color:red; font-weight:bold;">[HIGH ALERT]</span>@endif
        <br>
        <span style="margin-left:20px;">No. <strong>{{ $it->quantity }}</strong> {{ $it->unit }}</span>
        @if($it->frequency || $it->instructions || $it->duration)
            <br><span style="margin-left:20px;">S. {{ $it->frequency }} {{ $it->route }} {{ $it->duration }} {{ $it->instructions }}</span>
        @endif
        @if($prescription->is_iter && $prescription->iter_count > 0)
            <br><span style="margin-left:20px;">— iter {{ $prescription->iter_count }}x —</span>
        @endif
    </div>
    @endforeach
</div>

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>Hormat saya,</div>
        <div class="signature-space"></div>
        <div><strong>{{ $prescription->doctor->name ?? 'dr. ........................' }}</strong></div>
        @if($prescription->doctor?->str_number)<div class="small">STR: {{ $prescription->doctor->str_number }}</div>@endif
    </div>
</div>

<div class="small muted" style="margin-top:30px; border-top:1px dashed #aaa; padding-top:6px;">
    Pro re nata. Hanya boleh digunakan sesuai petunjuk dokter. Konsultasikan ke apoteker bila ragu.
</div>
@endsection
