@extends('layouts.print')
@section('title', 'Klaim '.$claim->claim_no)
@section('content')
<div class="doc-title">SURAT PENGAJUAN KLAIM ASURANSI</div>
<div class="doc-no">No: {{ $claim->claim_no }} &middot; Tgl: {{ $claim->claim_date?->translatedFormat('d F Y') }}</div>

<p>Kepada Yth.<br>
<strong>{{ $claim->insurance_provider }}</strong></p>

<p>Bersama ini kami sampaikan pengajuan klaim atas pasien:</p>
<table class="meta-table">
    <tr><td>Nama Pasien</td><td>: <strong>{{ $claim->patient->name ?? '-' }}</strong></td></tr>
    <tr><td>NIK</td><td>: {{ $claim->patient?->nik ?? '-' }}</td></tr>
    <tr><td>No. Polis / Peserta</td><td>: <strong>{{ $claim->policy_number ?? '-' }}</strong></td></tr>
    <tr><td>Jenis Pelayanan</td><td>: {{ ['outpatient'=>'Rawat Jalan','inpatient'=>'Rawat Inap','emergency'=>'IGD','maternity'=>'Persalinan'][$claim->claim_type] ?? $claim->claim_type }}</td></tr>
    <tr><td>Tanggal Pelayanan</td><td>: {{ $claim->service_date?->translatedFormat('d F Y') }}</td></tr>
    <tr><td>Diagnosis (ICD-10)</td><td>: {{ $claim->diagnosis_code }} - {{ $claim->diagnosis_text }}</td></tr>
</table>

<table class="data-table" style="margin-top:14px;">
    <tr><th>Jumlah Diajukan</th><td class="text-end">Rp {{ number_format((float) $claim->claimed_amount, 0, ',', '.') }}</td></tr>
    @if($claim->approved_amount !== null)
    <tr><th>Jumlah Disetujui</th><td class="text-end">Rp {{ number_format((float) $claim->approved_amount, 0, ',', '.') }}</td></tr>
    @endif
</table>

@if($claim->notes)<p class="small">Catatan: {{ $claim->notes }}</p>@endif

<p>Demikian pengajuan klaim ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>Petugas Klaim,</div>
        <div class="signature-space"></div>
        <div><strong>(......................)</strong></div>
    </div>
</div>
@endsection
