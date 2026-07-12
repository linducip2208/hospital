@extends('layouts.admin')
@section('title', 'Detail SP')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">SP <code>{{ $order->order_no }}</code></h1>
    <div>
        <a href="{{ route('drug-supply-orders.print', $order) }}" target="_blank" class="btn btn-success"><i class="bi bi-printer"></i> Cetak</a>
        <a href="{{ route('drug-supply-orders.edit', $order) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('drug-supply-orders.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<div class="card shadow-sm"><div class="card-body">
<table class="table"><tr><th>Jenis</th><td>{{ $order->order_type_label }}</td><th>Tanggal</th><td>{{ $order->order_date?->format('d M Y') }}</td></tr>
<tr><th>Supplier</th><td>{{ $order->supplier_name }}</td><th>No Izin</th><td>{{ $order->supplier_license_no }}</td></tr>
<tr><th>APJ</th><td>{{ $order->responsible_pharmacist }}</td><th>SIPA</th><td>{{ $order->pharmacist_sipa_no }}</td></tr>
</table>
<h5>Item</h5>
<table class="table table-bordered"><thead class="table-light"><tr><th>#</th><th>Obat</th><th>Bentuk</th><th>Kekuatan</th><th>Jumlah</th></tr></thead><tbody>
@foreach($order->items as $i => $it)
<tr><td>{{ $i+1 }}</td><td>{{ $it->drug_name }}</td><td>{{ $it->dose_form }}</td><td>{{ $it->strength }}</td><td>{{ $it->quantity }} {{ $it->unit }}</td></tr>
@endforeach
</tbody></table>
</div></div>
@endsection
