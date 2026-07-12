@extends('layouts.admin')
@section('title','Detail Estimasi')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Estimasi <code>{{ $estimate->estimate_no }}</code></h1>
    <div>
        <a href="{{ route('cost-estimates.print', $estimate) }}" target="_blank" class="btn btn-success"><i class="bi bi-printer"></i> Cetak</a>
        <a href="{{ route('cost-estimates.edit', $estimate) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('cost-estimates.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<div class="card shadow-sm"><div class="card-body">
<table class="table"><tr><th>Pasien</th><td>{{ $estimate->patient->name ?? '-' }}</td><th>Tanggal</th><td>{{ $estimate->estimate_date?->format('d M Y') }}</td></tr>
<tr><th>Dokter</th><td>{{ $estimate->doctor->name ?? '-' }}</td><th>Status</th><td>{{ $estimate->status }}</td></tr>
<tr><th>Tindakan</th><td colspan="3">{{ $estimate->procedure_name }}</td></tr></table>
<table class="table table-bordered">
<thead class="table-light"><tr><th>#</th><th>Deskripsi</th><th>Qty</th><th>Sat.</th><th class="text-end">Harga</th><th class="text-end">Subtotal</th></tr></thead>
<tbody>
@foreach($estimate->items as $i => $it)
<tr><td>{{ $i+1 }}</td><td>{{ $it->description }}</td><td>{{ $it->quantity }}</td><td>{{ $it->unit }}</td><td class="text-end">Rp {{ number_format((float) $it->unit_price,0,',','.') }}</td><td class="text-end">Rp {{ number_format((float) $it->subtotal,0,',','.') }}</td></tr>
@endforeach
</tbody>
<tfoot><tr><td colspan="5" class="text-end"><strong>Total</strong></td><td class="text-end"><strong>Rp {{ number_format((float) $estimate->total_amount,0,',','.') }}</strong></td></tr></tfoot>
</table>
</div></div>
@endsection
