@extends('layouts.admin')

@section('title', 'Kalibrasi Alat Kesehatan')

@section('content')
<div class="d-flex justify-content-between flex-wrap align-items-center page-header">
    <h1 class="h2"><i class="bi bi-tools"></i> Kalibrasi Alat Kesehatan</h1>
    <a href="{{ route('equipment-calibrations.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Catat Kalibrasi</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card shadow-sm border-0" style="border-left:4px solid #f43f5e!important"><div class="card-body"><div class="text-muted small">Terlambat (Overdue)</div><div class="h3 fw-bold text-danger">{{ $stats['overdue'] }}</div></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0" style="border-left:4px solid #f59e0b!important"><div class="card-body"><div class="text-muted small">Jatuh Tempo 30 Hari</div><div class="h3 fw-bold text-warning">{{ $stats['due_soon'] }}</div></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0" style="border-left:4px solid #2563eb!important"><div class="card-body"><div class="text-muted small">Total Catatan</div><div class="h3 fw-bold">{{ $stats['total'] }}</div></div></div></div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="mb-3">
            <a href="{{ route('equipment-calibrations.index') }}" class="btn btn-sm btn-outline-secondary {{ !request('filter') ? 'active' : '' }}">Semua</a>
            <a href="{{ route('equipment-calibrations.index', ['filter'=>'overdue']) }}" class="btn btn-sm btn-outline-danger {{ request('filter')==='overdue' ? 'active' : '' }}">Overdue</a>
            <a href="{{ route('equipment-calibrations.index', ['filter'=>'due_soon']) }}" class="btn btn-sm btn-outline-warning {{ request('filter')==='due_soon' ? 'active' : '' }}">Jatuh Tempo</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Alat</th><th>Tgl Kalibrasi</th><th>Jatuh Tempo</th><th>Hasil</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($calibrations as $c)
                    <tr class="{{ $c->isOverdue() ? 'table-danger' : '' }}">
                        <td>{{ $c->asset?->name ?? '-' }}<br><small class="text-muted">{{ $c->asset?->asset_code }}</small></td>
                        <td>{{ $c->calibration_date?->format('d/m/Y') }}</td>
                        <td>{{ $c->next_due_date?->format('d/m/Y') }}</td>
                        <td><span class="badge bg-{{ $c->result==='pass'?'success':($c->result==='fail'?'danger':'warning') }}">{{ $c->result }}</span></td>
                        <td><span class="badge bg-{{ $c->status==='completed'?'success':($c->status==='overdue'?'danger':'info') }}">{{ $c->status }}</span></td>
                        <td>
                            <a href="{{ route('equipment-calibrations.edit', $c) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('equipment-calibrations.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data kalibrasi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $calibrations->links() }}
    </div>
</div>
@endsection
