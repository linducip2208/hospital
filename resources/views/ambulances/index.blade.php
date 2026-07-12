@extends('layouts.admin')

@section('title', 'Armada Ambulans')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Armada Ambulans</h1>
    <a href="{{ route('ambulances.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ambulans Baru</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="available" @selected(request('status') === 'available')>Available</option>
                    <option value="on_duty" @selected(request('status') === 'on_duty')>On Duty</option>
                    <option value="maintenance" @selected(request('status') === 'maintenance')>Maintenance</option>
                    <option value="out_of_service" @selected(request('status') === 'out_of_service')>Out of Service</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('status'))
                    <a href="{{ route('ambulances.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>No. Kendaraan</th><th>Model</th><th>Tipe</th><th>Supir</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($ambulances as $ambulance)
                    <tr>
                        <td>{{ $loop->iteration + ($ambulances->currentPage() - 1) * $ambulances->perPage() }}</td>
                        <td><a href="{{ route('ambulances.show', $ambulance) }}" class="text-decoration-none">{{ $ambulance->vehicle_number }}</a></td>
                        <td>{{ $ambulance->model ?? '-' }}</td>
                        <td>{{ $ambulance->type ?? '-' }}</td>
                        <td>{{ $ambulance->driver_name ?? '-' }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'available' => 'success',
                                    'on_duty' => 'warning',
                                    'maintenance' => 'info',
                                    'out_of_service' => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$ambulance->status] ?? 'secondary' }}">
                                {{ str_replace('_', ' ', ucfirst($ambulance->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('ambulances.show', $ambulance) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('ambulances.edit', $ambulance) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('ambulances.destroy', $ambulance) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ambulans ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data ambulans</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $ambulances->links() }}
    </div>
</div>
@endsection
