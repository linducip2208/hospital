@extends('layouts.admin')

@section('title', 'Partograph')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Partograph</h1>
    <a href="{{ route('partographs.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Partograph</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-auto flex-grow-1">
                    <input type="text" name="search" class="form-control" placeholder="Cari pasien..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('partographs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Ibu Bersalin</th>
                        <th>Waktu Catat</th>
                        <th>Pembukaan (cm)</th>
                        <th>DJJ (bpm)</th>
                        <th>Kontraksi/10m</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($partographs as $partograph)
                    <tr>
                        <td>{{ $loop->iteration + ($partographs->currentPage() - 1) * $partographs->perPage() }}</td>
                        <td><a href="{{ route('partographs.show', $partograph) }}" class="text-decoration-none">{{ $partograph->maternity->patient->name ?? '-' }}</a></td>
                        <td>{{ $partograph->recorded_at ? $partograph->recorded_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ $partograph->cervical_dilation ? $partograph->cervical_dilation . ' cm' : '-' }}</td>
                        <td>{{ $partograph->fetal_heart_rate ? $partograph->fetal_heart_rate . ' bpm' : '-' }}</td>
                        <td>{{ $partograph->contractions_per_10min ?? '-' }}</td>
                        <td>
                            <a href="{{ route('partographs.show', $partograph) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('partographs.edit', $partograph) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('partographs.destroy', $partograph) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus partograph ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data partograph</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $partographs->links() }}
    </div>
</div>
@endsection
