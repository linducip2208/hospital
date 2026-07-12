@extends('layouts.admin')

@section('title', 'Detail Obat')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Obat</h1>
    <div>
        <a href="{{ route('drugs.edit', $drug) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('drugs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-capsule-pill"></i></div>
                <h5>{{ $drug->name }}</h5>
                <p class="text-muted mb-1">{{ $drug->category ?? '-' }}</p>
                <span class="badge bg-{{ $drug->stock > 0 && $drug->is_active ? 'success' : ($drug->stock <= 0 && $drug->is_active ? 'warning' : 'secondary') }}">
                    {{ $drug->stock > 0 && $drug->is_active ? 'Aktif' : ($drug->stock <= 0 && $drug->is_active ? 'Habis' : 'Nonaktif') }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Satuan:</strong> {{ $drug->unit ?? '-' }}</li>
                <li class="list-group-item"><strong>Stok:</strong> {{ $drug->stock ?? 0 }}</li>
                <li class="list-group-item"><strong>Harga:</strong> Rp {{ number_format($drug->price ?? 0, 0, ',', '.') }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Informasi</div>
            <div class="card-body">
                <p><strong>Deskripsi:</strong><br>{{ $drug->description ?? 'Tidak ada deskripsi' }}</p>
                @if($drug->notes)
                    <p><strong>Catatan:</strong><br>{{ $drug->notes }}</p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">Detail</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <tbody>
                        <tr><td style="width:200px"><strong>Nama Obat</strong></td><td>{{ $drug->name }}</td></tr>
                        <tr><td><strong>Kategori</strong></td><td>{{ $drug->category ?? '-' }}</td></tr>
                        <tr><td><strong>Satuan</strong></td><td>{{ $drug->unit ?? '-' }}</td></tr>
                        <tr><td><strong>Stok</strong></td><td>{{ $drug->stock ?? 0 }}</td></tr>
                        <tr><td><strong>Harga</strong></td><td>Rp {{ number_format($drug->price ?? 0, 0, ',', '.') }}</td></tr>
                        <tr><td><strong>Status</strong></td>
                            <td>
                                @if($drug->stock > 0 && $drug->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($drug->stock <= 0 && $drug->is_active)
                                    <span class="badge bg-warning">Habis</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
