@extends('layouts.admin')

@section('title', 'ANC Record')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">ANC Record</h1>
    <a href="{{ route('anc-records.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah ANC Record</a>
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
                        <a href="{{ route('anc-records.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
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
                        <th>Tgl Kunjungan</th>
                        <th>Usia Kehamilan</th>
                        <th>Tinggi Fundus</th>
                        <th>Kunjungan Berikutnya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ancRecords as $ancRecord)
                    <tr>
                        <td>{{ $loop->iteration + ($ancRecords->currentPage() - 1) * $ancRecords->perPage() }}</td>
                        <td><a href="{{ route('anc-records.show', $ancRecord) }}" class="text-decoration-none">{{ $ancRecord->patient->name ?? '-' }}</a></td>
                        <td>{{ $ancRecord->visit_date ? $ancRecord->visit_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $ancRecord->gestational_age ? $ancRecord->gestational_age . ' minggu' : '-' }}</td>
                        <td>{{ $ancRecord->fundal_height ? $ancRecord->fundal_height . ' cm' : '-' }}</td>
                        <td>{{ $ancRecord->next_visit_date ? $ancRecord->next_visit_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            <a href="{{ route('anc-records.show', $ancRecord) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('anc-records.edit', $ancRecord) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('anc-records.destroy', $ancRecord) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus ANC record ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data ANC record</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $ancRecords->links() }}
    </div>
</div>
@endsection
