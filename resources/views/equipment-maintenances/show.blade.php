@extends('layouts.admin')
@section('title','Detail Maintenance')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Maintenance #{{ $maintenance->id }}</h1><div><a href="{{ route('equipment-maintenances.edit', $maintenance) }}" class="btn btn-warning">Edit</a><a href="{{ route('equipment-maintenances.index') }}" class="btn btn-secondary">Kembali</a></div></div>
<div class="card shadow-sm"><div class="card-body"><table class="table">
<tr><th>Alat</th><td>{{ $maintenance->asset->name ?? '-' }}</td><th>Tipe</th><td>{{ $maintenance->maintenance_type }}</td></tr>
<tr><th>Jadwal</th><td>{{ $maintenance->scheduled_date?->format('d M Y') }}</td><th>Dilaksanakan</th><td>{{ $maintenance->performed_date?->format('d M Y') }}</td></tr>
<tr><th>Pelaksana</th><td>{{ $maintenance->performer }}</td><th>Biaya</th><td>Rp {{ number_format((float) $maintenance->cost, 0, ',', '.') }}</td></tr>
<tr><th>Hasil</th><td>{{ $maintenance->result }}</td><th>Status</th><td>{{ $maintenance->status }}</td></tr>
<tr><th>Berikutnya</th><td colspan="3">{{ $maintenance->next_due_date?->format('d M Y') }}</td></tr>
<tr><th>Deskripsi</th><td colspan="3">{!! nl2br(e($maintenance->description)) !!}</td></tr>
<tr><th>Temuan</th><td colspan="3">{!! nl2br(e($maintenance->findings)) !!}</td></tr>
<tr><th>Tindakan</th><td colspan="3">{!! nl2br(e($maintenance->action)) !!}</td></tr>
</table></div></div>
@endsection
