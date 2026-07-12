@extends('layouts.print')
@section('title','Rujukan')
@section('content')
<div class="doc-title">SURAT RUJUKAN</div>
<div class="doc-no">No: REF/{{ str_pad((string) $referral->id, 6, '0', STR_PAD_LEFT) }}</div>

<table style="width:100%; margin-bottom:10px;">
    <tr><td style="width:160px">Kepada Yth.</td><td>: <strong>{{ $referral->toPolyclinic->name ?? 'Poliklinik / RS Tujuan' }}</strong></td></tr>
    <tr><td>Dari</td><td>: {{ $referral->fromPolyclinic->name ?? '-' }}</td></tr>
</table>

<p>Dengan hormat, mohon kesediaan Sejawat untuk melakukan pemeriksaan/perawatan lanjutan terhadap pasien:</p>

<table class="meta-table">
    <tr><td>Nama</td><td>: <strong>{{ $referral->patient->name ?? '-' }}</strong></td></tr>
    <tr><td>NIK</td><td>: {{ $referral->patient?->nik ?? '-' }}</td></tr>
    <tr><td>Tgl Lahir</td><td>: {{ $referral->patient?->birth_date?->format('d/m/Y') }}</td></tr>
    <tr><td>Alamat</td><td>: {{ $referral->patient?->address ?? '-' }}</td></tr>
</table>

<table class="data-table" style="margin-top:10px;">
    <tr><th style="width:160px">Diagnosis Sementara</th><td>{{ $referral->diagnosis ?? '-' }}</td></tr>
    <tr><th>Alasan Rujukan</th><td>{{ $referral->reason }}</td></tr>
    <tr><th>Catatan</th><td>{{ $referral->notes }}</td></tr>
</table>

<p style="margin-top:14px;">Atas perhatian dan kerjasama Sejawat, kami ucapkan terima kasih.</p>

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>Hormat kami,</div>
        <div class="signature-space"></div>
        <div><strong>{{ $referral->doctor->name ?? '(....................)' }}</strong></div>
        @if($referral->doctor?->str_number)<div class="small">STR: {{ $referral->doctor->str_number }}</div>@endif
    </div>
</div>
@endsection
