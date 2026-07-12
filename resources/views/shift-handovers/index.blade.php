@extends('layouts.admin')

@section('title', 'Serah Terima Shift')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Serah Terima Shift</h1>
    <a href="{{ route('shift-handovers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Serah Terima</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-auto flex-grow-1">
                    <input type="text" name="search" class="form-control" placeholder="Cari perawat, shift..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('shift-handovers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Shift</th>
                        <th>Dari Perawat</th>
                        <th>Ke Perawat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shiftHandovers as $handover)
                    <tr>
                        <td>{{ $loop->iteration + ($shiftHandovers->currentPage() - 1) * $shiftHandovers->perPage() }}</td>
                        <td>{{ $handover->shift_date ? $handover->shift_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $handover->shift_type === 'pagi' ? 'info' : ($handover->shift_type === 'siang' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($handover->shift_type) }}
                            </span>
                        </td>
                        <td>{{ $handover->fromNurse->name ?? '-' }}</td>
                        <td>{{ $handover->toNurse->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('shift-handovers.show', $handover) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('shift-handovers.edit', $handover) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('shift-handovers.destroy', $handover) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data serah terima ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data serah terima shift</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $shiftHandovers->links() }}
    </div>
</div>
@endsection
