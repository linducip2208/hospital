@extends('layouts.admin')

@section('title', 'Imunisasi Bayi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Imunisasi Bayi</h1>
    <a href="{{ route('baby-immunizations.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Imunisasi</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-auto flex-grow-1">
                    <input type="text" name="search" class="form-control" placeholder="Cari vaksin, pasien..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('baby-immunizations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Bayi / Pasien</th>
                        <th>Vaksin</th>
                        <th>Dosis</th>
                        <th>Tgl Jadwal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($babyImmunizations as $babyImmunization)
                    <tr>
                        <td>{{ $loop->iteration + ($babyImmunizations->currentPage() - 1) * $babyImmunizations->perPage() }}</td>
                        <td><a href="{{ route('baby-immunizations.show', $babyImmunization) }}" class="text-decoration-none">{{ $babyImmunization->patient->name ?? '-' }}</a></td>
                        <td>{{ $babyImmunization->vaccine_name }}</td>
                        <td>{{ $babyImmunization->dose_number ?? '-' }}</td>
                        <td>{{ $babyImmunization->scheduled_date ? $babyImmunization->scheduled_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $babyImmunization->status === 'completed' ? 'success' : 'warning' }}">
                                {{ $babyImmunization->status === 'completed' ? 'Selesai' : 'Pending' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('baby-immunizations.show', $babyImmunization) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('baby-immunizations.edit', $babyImmunization) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('baby-immunizations.destroy', $babyImmunization) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus imunisasi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data imunisasi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $babyImmunizations->links() }}
    </div>
</div>
@endsection
