@extends('layouts.admin')

@section('title', 'Appointment')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Appointment</h1>
    <a href="{{ route('appointments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Appointment</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Cari pasien/dokter..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-auto">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="scheduled" @selected(request('status') === 'scheduled')>Scheduled</option>
                    <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
                    <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                    <option value="no_show" @selected(request('status') === 'no_show')>No Show</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('date') || request('status'))
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Pasien</th><th>Dokter</th><th>Treatment</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                    <tr>
                        <td>{{ $loop->iteration + ($appointments->currentPage() - 1) * $appointments->perPage() }}</td>
                        <td><a href="{{ route('patients.show', $appointment->patient) }}" class="text-decoration-none">{{ $appointment->patient->name ?? '-' }}</a></td>
                        <td>{{ $appointment->doctor->name ?? '-' }}</td>
                        <td>{{ $appointment->treatment->name ?? '-' }}</td>
                        <td>{{ $appointment->appointment_date->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' || $appointment->status === 'no_show' ? 'danger' : ($appointment->status === 'confirmed' ? 'primary' : ($appointment->status === 'in_progress' ? 'info' : 'warning'))) }}">
                                {{ str_replace('_', ' ', ucfirst($appointment->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus appointment ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada appointment</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $appointments->links() }}
    </div>
</div>
@endsection