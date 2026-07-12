@extends('layouts.admin')

@section('title', 'Farmasi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Farmasi</h1>
    <a href="{{ route('drugs.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Obat</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Cari nama obat..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    <option value="Tablet" @selected(request('category') === 'Tablet')>Tablet</option>
                    <option value="Sirup" @selected(request('category') === 'Sirup')>Sirup</option>
                    <option value="Salep" @selected(request('category') === 'Salep')>Salep</option>
                    <option value="Injeksi" @selected(request('category') === 'Injeksi')>Injeksi</option>
                    <option value="Kapsul" @selected(request('category') === 'Kapsul')>Kapsul</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('category'))
                    <a href="{{ route('drugs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Nama</th><th>Kategori</th><th>Satuan</th><th>Stok</th><th>Harga</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($drugs as $drug)
                    <tr>
                        <td>{{ $loop->iteration + ($drugs->currentPage() - 1) * $drugs->perPage() }}</td>
                        <td><a href="{{ route('drugs.show', $drug) }}" class="text-decoration-none">{{ $drug->name }}</a></td>
                        <td>{{ $drug->category ?? '-' }}</td>
                        <td>{{ $drug->unit ?? '-' }}</td>
                        <td>{{ $drug->stock ?? 0 }}</td>
                        <td>Rp {{ number_format($drug->price ?? 0, 0, ',', '.') }}</td>
                        <td>
                            @if($drug->stock > 0 && $drug->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @elseif($drug->stock <= 0 && $drug->is_active)
                                <span class="badge bg-warning">Habis</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('drugs.show', $drug) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('drugs.edit', $drug) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('drugs.destroy', $drug) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus obat ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data obat</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $drugs->links() }}
    </div>
</div>
@endsection
