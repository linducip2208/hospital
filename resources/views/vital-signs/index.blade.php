@extends('layouts.admin')

@section('title', 'Tanda Vital')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Tanda Vital</h1>
    <a href="{{ route('vital-signs.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Tanda Vital</a>
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
                        <a href="{{ route('vital-signs.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Pasien</th>
                        <th>Suhu (°C)</th>
                        <th>Tek. Darah</th>
                        <th>Nadi (bpm)</th>
                        <th>SpO₂ (%)</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vitalSigns as $record)
                    <tr>
                        <td>{{ $loop->iteration + ($vitalSigns->currentPage() - 1) * $vitalSigns->perPage() }}</td>
                        <td><a href="{{ route('vital-signs.show', $record) }}" class="text-decoration-none">{{ $record->patient->name ?? '-' }}</a></td>
                        <td>{{ $record->temperature ?? '-' }}</td>
                        <td>{{ $record->blood_pressure_systolic ?? '-' }}/{{ $record->blood_pressure_diastolic ?? '-' }}</td>
                        <td>{{ $record->heart_rate ?? '-' }}</td>
                        <td>{{ $record->oxygen_saturation ?? '-' }}</td>
                        <td>{{ $record->recorded_at ? $record->recorded_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <a href="{{ route('vital-signs.show', $record) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('vital-signs.edit', $record) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('vital-signs.destroy', $record) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data tanda vital ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data tanda vital</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $vitalSigns->links() }}
    </div>
</div>
@endsection
