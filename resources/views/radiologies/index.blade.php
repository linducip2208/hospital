@extends('layouts.admin')

@section('title', 'Radiologi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Radiologi</h1>
    <a href="{{ route('radiologies.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Pemeriksaan Baru</a>
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
                    <option value="requested" @selected(request('status') === 'requested')>Requested</option>
                    <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('status'))
                    <a href="{{ route('radiologies.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Pasien</th><th>Dokter</th><th>Pemeriksaan</th><th>Bagian Tubuh</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($radiologies as $radiology)
                    <tr>
                        <td>{{ $loop->iteration + ($radiologies->currentPage() - 1) * $radiologies->perPage() }}</td>
                        <td><a href="{{ route('radiologies.show', $radiology) }}" class="text-decoration-none">{{ $radiology->patient->name ?? '-' }}</a></td>
                        <td>{{ $radiology->doctor->name ?? '-' }}</td>
                        <td>{{ $radiology->examination_name }}</td>
                        <td>{{ $radiology->body_part ?? '-' }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'requested' => 'secondary',
                                    'in_progress' => 'warning',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$radiology->status] ?? 'secondary' }}">
                                {{ str_replace('_', ' ', ucfirst($radiology->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('radiologies.show', $radiology) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('radiologies.edit', $radiology) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('radiologies.destroy', $radiology) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pemeriksaan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pemeriksaan radiologi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $radiologies->links() }}
    </div>
</div>
@endsection
