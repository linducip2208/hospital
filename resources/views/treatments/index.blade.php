@extends('layouts.admin')

@section('title', 'Data Treatment')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Data Treatment</h1>
    <a href="{{ route('treatments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Treatment</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Cari treatment..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('category'))
                    <a href="{{ route('treatments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Durasi</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($treatments as $treatment)
                    <tr>
                        <td>{{ $loop->iteration + ($treatments->currentPage() - 1) * $treatments->perPage() }}</td>
                        <td><a href="{{ route('treatments.show', $treatment) }}" class="text-decoration-none">{{ $treatment->name }}</a></td>
                        <td>{{ $treatment->category ?? '-' }}</td>
                        <td>Rp {{ number_format($treatment->price ?? 0, 0, ',', '.') }}</td>
                        <td>{{ $treatment->duration_minutes ? $treatment->duration_minutes . ' menit' : '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $treatment->is_active ? 'success' : 'secondary' }}">
                                {{ $treatment->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('treatments.edit', $treatment) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('treatments.destroy', $treatment) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus treatment ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data treatment</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $treatments->links() }}
    </div>
</div>
@endsection
