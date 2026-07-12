@extends('layouts.admin')
@section('title','Detail Diet')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Order Diet <code>{{ $order->order_no }}</code></h1><div><a href="{{ route('diet-orders.edit', $order) }}" class="btn btn-warning">Edit</a><a href="{{ route('diet-orders.index') }}" class="btn btn-secondary">Kembali</a></div></div>
<div class="card shadow-sm"><div class="card-body"><table class="table">
<tr><th>Pasien</th><td>{{ $order->patient->name ?? '-' }}</td><th>Dokter</th><td>{{ $order->doctor->name ?? '-' }}</td></tr>
<tr><th>Diet</th><td>{{ \App\Models\DietOrder::DIET_TYPES[$order->diet_type] }}</td><th>Kalori</th><td>{{ $order->calories ?? '-' }} kcal</td></tr>
<tr><th>Mulai</th><td>{{ $order->start_date?->format('d M Y') }}</td><th>Selesai</th><td>{{ $order->end_date?->format('d M Y') ?? '-' }}</td></tr>
<tr><th>Status</th><td colspan="3">{{ $order->status }}</td></tr>
<tr><th>Instruksi Khusus</th><td colspan="3">{!! nl2br(e($order->special_instructions)) !!}</td></tr>
</table></div></div>
@endsection
