@extends('layouts.admin')

@section('title', 'Postnatal Record')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Postnatal Record</h1>
    <a href="{{ route('postnatal-records.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Postnatal Record</a>
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
                        <a href="{{ route('postnatal-records.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
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
                        <th>Berat Bayi</th>
                        <th>Menyusui</th>
                        <th>Komplikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($postnatalRecords as $postnatalRecord)
                    <tr>
                        <td>{{ $loop->iteration + ($postnatalRecords->currentPage() - 1) * $postnatalRecords->perPage() }}</td>
                        <td><a href="{{ route('postnatal-records.show', $postnatalRecord) }}" class="text-decoration-none">{{ $postnatalRecord->patient->name ?? '-' }}</a></td>
                        <td>{{ $postnatalRecord->visit_date ? $postnatalRecord->visit_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $postnatalRecord->baby_weight ? $postnatalRecord->baby_weight . ' kg' : '-' }}</td>
                        <td>
                            @php
                                $bfColors = ['good' => 'success', 'fair' => 'warning', 'poor' => 'danger'];
                            @endphp
                            <span class="badge bg-{{ $bfColors[$postnatalRecord->breastfeeding] ?? 'secondary' }}">
                                {{ $postnatalRecord->breastfeeding ? ucfirst($postnatalRecord->breastfeeding) : '-' }}
                            </span>
                        </td>
                        <td>
                            @if($postnatalRecord->complications)
                                <span class="badge bg-warning">Ada</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('postnatal-records.show', $postnatalRecord) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('postnatal-records.edit', $postnatalRecord) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('postnatal-records.destroy', $postnatalRecord) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus postnatal record ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data postnatal record</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $postnatalRecords->links() }}
    </div>
</div>
@endsection
