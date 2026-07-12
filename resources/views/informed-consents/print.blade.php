@extends('layouts.print')
@section('title', $consent->kind_label . ' - ' . $consent->consent_no)
@section('content')
@php
    $title = match($consent->kind) {
        'consent' => 'SURAT PERSETUJUAN TINDAKAN MEDIS',
        'refusal' => 'SURAT PENOLAKAN TINDAKAN MEDIS',
        'aps' => 'SURAT PERNYATAAN PULANG ATAS PERMINTAAN SENDIRI',
    };
    $patient = $consent->patient;
    $verb = $consent->kind === 'refusal' ? 'MENOLAK' : ($consent->kind === 'aps' ? 'pulang atas kemauan sendiri' : 'MENYETUJUI');
@endphp
<div class="doc-title">{{ $title }}</div>
<div class="doc-no">No: {{ $consent->consent_no }}</div>

<p>Yang bertanda tangan di bawah ini:</p>
<table class="meta-table">
    <tr><td>Nama</td><td>: <strong>{{ $consent->signed_by_name ?? $patient?->name }}</strong></td></tr>
    <tr><td>Hubungan dengan Pasien</td><td>: {{ $consent->signed_by_relation ?? 'Diri sendiri' }}</td></tr>
</table>

<p>Dengan ini menyatakan dengan sesungguhnya, setelah mendapat penjelasan secukupnya dari dokter mengenai:</p>

<table class="meta-table">
    <tr><td>Nama Pasien</td><td>: <strong>{{ $patient?->name }}</strong></td></tr>
    <tr><td>NIK</td><td>: {{ $patient?->nik ?? '-' }}</td></tr>
    <tr><td>Tgl Lahir</td><td>: {{ $patient?->birth_date?->format('d/m/Y') ?? '-' }}</td></tr>
    <tr><td>Alamat</td><td>: {{ $patient?->address ?? '-' }}</td></tr>
</table>

<p>Bahwa terhadap pasien tersebut akan/telah dilakukan tindakan:</p>
<p><strong>{{ $consent->procedure_name }}</strong></p>

@if($consent->procedure_description)
<p><u>Penjelasan tindakan:</u><br>{!! nl2br(e($consent->procedure_description)) !!}</p>
@endif

@if($consent->risks)
<p><u>Risiko & komplikasi yang mungkin timbul:</u><br>{!! nl2br(e($consent->risks)) !!}</p>
@endif

@if($consent->alternatives)
<p><u>Alternatif tindakan:</u><br>{!! nl2br(e($consent->alternatives)) !!}</p>
@endif

<p style="margin-top:14px;">
    Saya <strong>{{ $verb }}</strong> dilakukannya tindakan tersebut dengan segala konsekuensinya
    @if($consent->kind === 'refusal' || $consent->kind === 'aps')
        dan saya bertanggung jawab penuh atas keputusan ini serta membebaskan dokter dan rumah sakit dari segala tuntutan akibat keputusan ini.
    @else
        dan tidak akan menuntut bila terjadi hal-hal yang tidak diinginkan sepanjang sesuai dengan prosedur medis yang berlaku.
    @endif
</p>

<table style="width:100%; margin-top:30px;">
    <tr>
        <td style="width:33%; text-align:center; vertical-align:top;">
            <div>Saksi,</div>
            <div class="signature-space"></div>
            <div><strong>{{ $consent->witness_name ?? '(......................)' }}</strong></div>
        </td>
        <td style="width:33%; text-align:center; vertical-align:top;">
            <div>Dokter / DPJP,</div>
            <div class="signature-space"></div>
            <div><strong>{{ $consent->doctor->name ?? '(......................)' }}</strong></div>
            @if($consent->doctor?->str_number)<div class="small">STR: {{ $consent->doctor->str_number }}</div>@endif
        </td>
        <td style="width:33%; text-align:center; vertical-align:top;">
            <div>Yang Membuat Pernyataan,</div>
            <div class="signature-space"></div>
            <div><strong>{{ $consent->signed_by_name ?? '(......................)' }}</strong></div>
        </td>
    </tr>
</table>
@endsection
