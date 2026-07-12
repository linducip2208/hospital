@extends('layouts.admin')

@section('title', 'Operasi / OK')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Operasi / OK</h1>
    <a href="{{ route('surgeries.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Operasi Baru</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="date" name="scheduled_date" class="form-control" value="{{ request('scheduled_date') }}" placeholder="Filter tanggal operasi">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="scheduled" @selected(request('status') === 'scheduled')>Scheduled</option>
                    <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('scheduled_date') || request('status'))
                    <a href="{{ route('surgeries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Pasien</th><th>Nama Operasi</th><th>Dokter</th><th>Tgl Operasi</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($surgeries as $surgery)
                    <tr>
                        <td>{{ $loop->iteration + ($surgeries->currentPage() - 1) * $surgeries->perPage() }}</td>
                        <td><a href="{{ route('surgeries.show', $surgery) }}" class="text-decoration-none">{{ $surgery->patient->name ?? '-' }}</a></td>
                        <td>{{ Str::limit($surgery->name, 40) }}</td>
                        <td>{{ $surgery->doctor->name ?? '-' }}</td>
                        <td>{{ $surgery->scheduled_date ? $surgery->scheduled_date->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'scheduled' => 'info',
                                    'in_progress' => 'warning',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$surgery->status] ?? 'secondary' }}">
                                {{ str_replace('_', ' ', ucfirst($surgery->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('surgeries.show', $surgery) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('surgeries.edit', $surgery) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('surgeries.destroy', $surgery) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data operasi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data operasi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $surgeries->links() }}
    </div>
</div>
@endsection
