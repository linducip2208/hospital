@extends('layouts.admin')
@section('title', 'Detail Resep')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Resep <code>{{ $prescription->rx_no }}</code></h1>
    <div>
        <a href="{{ route('prescriptions.print', $prescription) }}" target="_blank" class="btn btn-success"><i class="bi bi-printer"></i> Cetak Resep</a>
        <a href="{{ route('prescriptions.print-labels', $prescription) }}" target="_blank" class="btn btn-outline-success"><i class="bi bi-tag"></i> Cetak Etiket</a>
        <a href="{{ route('prescriptions.edit', $prescription) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('prescriptions.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<div class="card shadow-sm"><div class="card-body">
<table class="table table-sm">
    <tr><th>Pasien</th><td>{{ $prescription->patient->name ?? '-' }}</td><th>Tanggal</th><td>{{ $prescription->prescribed_at?->format('d M Y') }}</td></tr>
    <tr><th>Dokter</th><td>{{ $prescription->doctor->name ?? '-' }}</td><th>Iter</th><td>{{ $prescription->is_iter ? 'Ya, '.$prescription->iter_count.'x' : 'Tidak' }}</td></tr>
</table>
<h5>Item Obat</h5>
<table class="table table-bordered">
    <thead class="table-light"><tr><th>R/</th><th>Obat</th><th>Dosis</th><th>Frek</th><th>Lama</th><th>Qty</th><th>Aturan</th></tr></thead>
    <tbody>
        @foreach($prescription->items as $i => $it)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $it->drug_name }} @if($it->is_high_alert)<span class="badge bg-danger">HIGH ALERT</span>@endif @if($it->is_compounded)<span class="badge bg-info">RACIK</span>@endif</td>
            <td>{{ $it->dose }}</td>
            <td>{{ $it->frequency }}</td>
            <td>{{ $it->duration }}</td>
            <td>{{ $it->quantity }} {{ $it->unit }}</td>
            <td>{{ $it->instructions }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@if($prescription->notes)<p><strong>Catatan:</strong> {{ $prescription->notes }}</p>@endif
</div></div>
@endsection
