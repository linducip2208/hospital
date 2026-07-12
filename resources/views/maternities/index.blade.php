@extends('layouts.admin')

@section('title', 'Ruang Bersalin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Ruang Bersalin</h1>
    <a href="{{ route('maternities.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Catat Persalinan</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Cari pasien..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="admitted" @selected(request('status') === 'admitted')>Admitted</option>
                    <option value="in_labor" @selected(request('status') === 'in_labor')>In Labor</option>
                    <option value="delivered" @selected(request('status') === 'delivered')>Delivered</option>
                    <option value="postpartum" @selected(request('status') === 'postpartum')>Postpartum</option>
                    <option value="discharged" @selected(request('status') === 'discharged')>Discharged</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('status'))
                    <a href="{{ route('maternities.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Pasien</th><th>Dokter</th><th>Tgl Masuk</th><th>Tgl Lahir</th><th>Metode</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($maternities as $maternity)
                    <tr>
                        <td>{{ $loop->iteration + ($maternities->currentPage() - 1) * $maternities->perPage() }}</td>
                        <td><a href="{{ route('maternities.show', $maternity) }}" class="text-decoration-none">{{ $maternity->patient->name ?? '-' }}</a></td>
                        <td>{{ $maternity->doctor->name ?? '-' }}</td>
                        <td>{{ $maternity->admission_date ? $maternity->admission_date->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ $maternity->delivery_date ? $maternity->delivery_date->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ ucfirst($maternity->delivery_type ?? '-') }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'admitted' => 'info',
                                    'in_labor' => 'warning',
                                    'delivered' => 'success',
                                    'postpartum' => 'primary',
                                    'discharged' => 'secondary',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$maternity->status] ?? 'secondary' }}">
                                {{ str_replace('_', ' ', ucfirst($maternity->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('maternities.show', $maternity) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('maternities.edit', $maternity) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('maternities.destroy', $maternity) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data persalinan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data persalinan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $maternities->links() }}
    </div>
</div>
@endsection
