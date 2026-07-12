@extends('layouts.admin')

@section('title', 'Limbah Medis B3')

@section('content')
<div class="d-flex justify-content-between flex-wrap align-items-center page-header">
    <h1 class="h2"><i class="bi bi-biohazard"></i> Limbah Medis B3</h1>
    <a href="{{ route('medical-wastes.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Manifest</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card shadow-sm border-0" style="border-left:4px solid #f43f5e!important"><div class="card-body"><div class="text-muted small">Total Limbah Bulan Ini</div><div class="h3 fw-bold">{{ number_format($stats['total_kg_month'],1) }} kg</div></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0" style="border-left:4px solid #f59e0b!important"><div class="card-body"><div class="text-muted small">Tersimpan (Belum Dibuang)</div><div class="h3 fw-bold text-warning">{{ $stats['stored'] }}</div></div></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0" style="border-left:4px solid #10b981!important"><div class="card-body"><div class="text-muted small">Dibuang Bulan Ini</div><div class="h3 fw-bold text-success">{{ $stats['disposed_month'] }}</div></div></div></div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="waste_type" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Jenis</option>
                    @foreach($typeLabels as $k=>$v)<option value="{{ $k }}" @selected(request('waste_type')===$k)>{{ $v }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    @foreach(['stored'=>'Tersimpan','transported'=>'Diangkut','disposed'=>'Dibuang'] as $k=>$v)<option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>@endforeach
                </select>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Manifest</th><th>Jenis</th><th>Berat</th><th>Unit</th><th>Tgl Kumpul</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($wastes as $w)
                    <tr>
                        <td><code>{{ $w->manifest_no }}</code></td>
                        <td>{{ $typeLabels[$w->waste_type] ?? $w->waste_type }}</td>
                        <td>{{ number_format($w->weight_kg,2) }} kg</td>
                        <td>{{ $w->department?->name ?? '-' }}</td>
                        <td>{{ $w->collection_date?->format('d/m/Y') }}</td>
                        <td><span class="badge bg-{{ $w->status==='disposed'?'success':($w->status==='transported'?'info':'warning') }}">{{ $w->status }}</span></td>
                        <td>
                            <a href="{{ route('medical-wastes.edit', $w) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('medical-wastes.destroy', $w) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data limbah medis</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $wastes->links() }}
    </div>
</div>
@endsection
