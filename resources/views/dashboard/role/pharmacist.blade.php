@extends('layouts.admin')

@section('title', 'Dashboard Farmasi')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Selamat datang, {{ $user->name }}</h1>
    <p class="text-muted mb-0">Dashboard apotek & farmasi — {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #f43f5e!important">
            <div class="card-body"><div class="text-muted small">Stok Menipis</div><div class="h3 fw-bold mb-0 text-danger">{{ $lowStock }}</div></div>
        </div>
    </div>
    <div class="col-md-4 col-6">
        <div class="card shadow-sm border-0" style="border-left:4px solid #2563eb!important">
            <div class="card-body"><div class="text-muted small">Total Jenis Obat</div><div class="h3 fw-bold mb-0">{{ $totalDrugs }}</div></div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="card shadow-sm border-0" style="border-left:4px solid #10b981!important">
            <div class="card-body"><div class="text-muted small">Resep Hari Ini</div><div class="h3 fw-bold mb-0">{{ $prescriptionsToday }}</div></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold text-danger"><i class="bi bi-exclamation-triangle"></i> Obat Stok Menipis (Perlu Reorder)</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th>Nama Obat</th><th>Stok</th><th></th></tr></thead>
                        <tbody>
                            @forelse($lowStockDrugs as $d)
                            <tr>
                                <td>{{ $d->name }}</td>
                                <td><span class="badge bg-danger">{{ $d->stock }}</span></td>
                                <td><a href="{{ route('drug-supply-orders.create') }}" class="btn btn-sm btn-outline-primary">Pesan</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Semua stok aman</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Aksi Cepat</h6>
                <a href="{{ route('drugs.index') }}" class="btn btn-primary w-100 mb-2"><i class="bi bi-box-seam"></i> Inventaris Obat</a>
                <a href="{{ route('drug-supply-orders.create') }}" class="btn btn-outline-primary w-100 mb-2"><i class="bi bi-file-earmark-plus"></i> Surat Pesanan Obat</a>
                <a href="{{ route('prescriptions.index') }}" class="btn btn-outline-primary w-100 mb-2"><i class="bi bi-prescription2"></i> Daftar Resep</a>
                <a href="{{ route('drug-destructions.index') }}" class="btn btn-outline-primary w-100"><i class="bi bi-trash3"></i> Pemusnahan Obat</a>
            </div>
        </div>
    </div>
</div>
@endsection
