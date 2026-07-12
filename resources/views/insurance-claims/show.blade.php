@extends('layouts.admin')
@section('title','Detail Klaim')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Klaim <code>{{ $claim->claim_no }}</code></h1>
    <div>
        <a href="{{ route('insurance-claims.print', $claim) }}" target="_blank" class="btn btn-success"><i class="bi bi-printer"></i> Cetak</a>
        <a href="{{ route('insurance-claims.edit', $claim) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('insurance-claims.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<div class="card shadow-sm"><div class="card-body">
<table class="table">
<tr><th>Pasien</th><td>{{ $claim->patient->name ?? '-' }}</td><th>Provider</th><td>{{ $claim->insurance_provider }}</td></tr>
<tr><th>No. Polis</th><td>{{ $claim->policy_number }}</td><th>Tipe</th><td>{{ $claim->claim_type }}</td></tr>
<tr><th>Tgl Pelayanan</th><td>{{ $claim->service_date?->format('d M Y') }}</td><th>Tgl Klaim</th><td>{{ $claim->claim_date?->format('d M Y') }}</td></tr>
<tr><th>Diagnosis</th><td colspan="3">{{ $claim->diagnosis_code }} - {{ $claim->diagnosis_text }}</td></tr>
<tr><th>Diajukan</th><td>Rp {{ number_format((float) $claim->claimed_amount,0,',','.') }}</td><th>Disetujui</th><td>Rp {{ number_format((float) ($claim->approved_amount ?? 0),0,',','.') }}</td></tr>
<tr><th>Status</th><td colspan="3">{{ $claim->status }}</td></tr>
</table>
</div></div>
@endsection
