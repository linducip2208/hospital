@extends('layouts.print')
@section('title', 'BA Pemusnahan '.$destruction->destruction_no)
@section('content')
<div class="doc-title">BERITA ACARA PEMUSNAHAN OBAT</div>
<div class="doc-no">No: {{ $destruction->destruction_no }}</div>

<p>Pada hari ini, {{ $destruction->destruction_date?->translatedFormat('l, d F Y') }}, telah dilaksanakan pemusnahan obat-obatan di {{ \App\Models\Setting::where('key','branding_app_name')->value('value') ?? config('app.name') }} sebagai berikut:</p>

<table class="meta-table">
    <tr><td>Lokasi</td><td>: {{ $destruction->location }}</td></tr>
    <tr><td>Metode Pemusnahan</td><td>: {{ $destruction->method }}</td></tr>
    <tr><td>Alasan</td><td>: {{ $destruction->reason }}</td></tr>
</table>

<p>Dengan rincian obat sebagai berikut:</p>
<table class="data-table">
    <thead><tr><th style="width:40px">No</th><th>Nama Obat</th><th>No. Batch</th><th>ED</th><th>Jumlah</th><th>Alasan</th></tr></thead>
    <tbody>
    @foreach($destruction->items as $i => $it)
        <tr><td>{{ $i+1 }}</td><td>{{ $it->drug_name }}</td><td>{{ $it->batch_no }}</td><td>{{ $it->expired_at?->format('d/m/Y') }}</td><td>{{ $it->quantity }} {{ $it->unit }}</td><td>{{ $it->reason }}</td></tr>
    @endforeach
    </tbody>
</table>

<p>Berita Acara ini dibuat dengan sebenar-benarnya untuk dipergunakan sebagaimana mestinya.</p>

<table style="width:100%; margin-top:30px;">
    <tr>
        <td style="width:33%; text-align:center; vertical-align:top;">
            <div>Apoteker Penanggung Jawab</div>
            <div class="signature-space"></div>
            <div><strong>{{ $destruction->responsible_pharmacist }}</strong></div>
        </td>
        <td style="width:33%; text-align:center; vertical-align:top;">
            <div>Saksi 1</div>
            <div class="small">({{ $destruction->witness_role_1 }})</div>
            <div class="signature-space"></div>
            <div><strong>{{ $destruction->witness_name_1 }}</strong></div>
        </td>
        <td style="width:33%; text-align:center; vertical-align:top;">
            <div>Saksi 2</div>
            <div class="small">({{ $destruction->witness_role_2 }})</div>
            <div class="signature-space"></div>
            <div><strong>{{ $destruction->witness_name_2 }}</strong></div>
        </td>
    </tr>
</table>
@endsection
