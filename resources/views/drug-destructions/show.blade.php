@extends('layouts.admin')
@section('title', 'Detail BA Pemusnahan')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">BA <code>{{ $destruction->destruction_no }}</code></h1>
    <div>
        <a href="{{ route('drug-destructions.print', $destruction) }}" target="_blank" class="btn btn-success"><i class="bi bi-printer"></i> Cetak</a>
        <a href="{{ route('drug-destructions.edit', $destruction) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('drug-destructions.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<div class="card shadow-sm"><div class="card-body">
<table class="table">
<tr><th>Tanggal</th><td>{{ $destruction->destruction_date?->format('d M Y') }}</td><th>Lokasi</th><td>{{ $destruction->location }}</td></tr>
<tr><th>Metode</th><td>{{ $destruction->method }}</td><th>APJ</th><td>{{ $destruction->responsible_pharmacist }}</td></tr>
<tr><th>Saksi 1</th><td>{{ $destruction->witness_name_1 }} ({{ $destruction->witness_role_1 }})</td><th>Saksi 2</th><td>{{ $destruction->witness_name_2 }} ({{ $destruction->witness_role_2 }})</td></tr>
<tr><th>Alasan</th><td colspan="3">{{ $destruction->reason }}</td></tr>
</table>
<h5>Daftar Obat</h5>
<table class="table table-bordered"><thead class="table-light"><tr><th>#</th><th>Obat</th><th>Batch</th><th>ED</th><th>Jumlah</th><th>Alasan</th></tr></thead><tbody>
@foreach($destruction->items as $i => $it)
<tr><td>{{ $i+1 }}</td><td>{{ $it->drug_name }}</td><td>{{ $it->batch_no }}</td><td>{{ $it->expired_at?->format('d M Y') }}</td><td>{{ $it->quantity }} {{ $it->unit }}</td><td>{{ $it->reason }}</td></tr>
@endforeach
</tbody></table>
</div></div>
@endsection
