@extends('layouts.admin')

@section('title', 'Panggilan Ambulans')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Panggilan Ambulans</h1>
    <a href="{{ route('ambulance-calls.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Panggilan Baru</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="dispatched" @selected(request('status') === 'dispatched')>Dispatched</option>
                    <option value="en_route" @selected(request('status') === 'en_route')>En Route</option>
                    <option value="arrived" @selected(request('status') === 'arrived')>Arrived</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('status'))
                    <a href="{{ route('ambulance-calls.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Pasien</th><th>Ambulans</th><th>Lokasi Jemput</th><th>Tujuan</th><th>Tgl Panggil</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($calls as $call)
                    <tr>
                        <td>{{ $loop->iteration + ($calls->currentPage() - 1) * $calls->perPage() }}</td>
                        <td><a href="{{ route('ambulance-calls.show', $call) }}" class="text-decoration-none">{{ $call->patient_name }}</a></td>
                        <td>{{ $call->ambulance->vehicle_number ?? '-' }}</td>
                        <td>{{ Str::limit($call->pickup_location, 30) }}</td>
                        <td>{{ Str::limit($call->destination, 25) }}</td>
                        <td>{{ $call->call_date ? $call->call_date->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'secondary',
                                    'dispatched' => 'info',
                                    'en_route' => 'warning',
                                    'arrived' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$call->status] ?? 'secondary' }}">
                                {{ str_replace('_', ' ', ucfirst($call->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('ambulance-calls.show', $call) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('ambulance-calls.edit', $call) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('ambulance-calls.destroy', $call) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus panggilan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada panggilan ambulans</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $calls->links() }}
    </div>
</div>
@endsection
