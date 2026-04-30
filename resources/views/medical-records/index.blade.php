@extends('layouts.admin')

@section('title', 'Rekam Medis')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Rekam Medis</h1>
    <a href="{{ route('medical-records.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Rekam Medis</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col">
                    <input type="text" name="search" class="form-control" placeholder="Cari pasien/diagnosis..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Pasien</th><th>Dokter</th><th>Diagnosis</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    <tr>
                        <td>{{ $record->iteration + ($record->currentPage() - 1) * $record->perPage() }}</td>
                        <td><a href="{{ route('patients.show', $record->patient) }}">{{ $record->patient->name ?? '-' }}</a></td>
                        <td>{{ $record->doctor->name ?? '-' }}</td>
                        <td>{{ Str::limit($record->diagnosis, 40) }}</td>
                        <td>{{ $record->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('medical-records.show', $record) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('medical-records.edit', $record) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('medical-records.destroy', $record) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus rekam medis?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada rekam medis</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $records->links() }}
    </div>
</div>
@endsection
