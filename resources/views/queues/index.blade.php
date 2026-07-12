@extends('layouts.admin')

@section('title', 'Antrian Poli')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Antrian Poli</h1>
    <a href="{{ route('queues.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Antrian</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col">
                <select name="polyclinic_id" class="form-select">
                    <option value="">Semua Poli</option>
                    @foreach($polyclinics as $polyclinic)
                        <option value="{{ $polyclinic->id }}" @selected(request('polyclinic_id') == $polyclinic->id)>{{ $polyclinic->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="waiting" @selected(request('status') === 'waiting')>Menunggu</option>
                    <option value="called" @selected(request('status') === 'called')>Dipanggil</option>
                    <option value="in_progress" @selected(request('status') === 'in_progress')>Diperiksa</option>
                    <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Batal</option>
                </select>
            </div>
            <div class="col">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('polyclinic_id') || request('status') || request('date'))
                    <a href="{{ route('queues.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>No Antrian</th>
                        <th>Poli</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($queues as $queue)
                    <tr>
                        <td>{{ $loop->iteration + ($queues->currentPage() - 1) * $queues->perPage() }}</td>
                        <td><strong>{{ $queue->queue_number }}</strong></td>
                        <td>{{ $queue->polyclinic->name ?? '-' }}</td>
                        <td><a href="{{ route('patients.show', $queue->patient) }}" class="text-decoration-none">{{ $queue->patient->name ?? '-' }}</a></td>
                        <td>{{ $queue->doctor->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $queue->status === 'waiting' ? 'warning' : ($queue->status === 'called' ? 'info' : ($queue->status === 'in_progress' ? 'primary' : ($queue->status === 'completed' ? 'success' : 'danger'))) }}">
                                {{ $queue->status === 'waiting' ? 'Menunggu' : ($queue->status === 'called' ? 'Dipanggil' : ($queue->status === 'in_progress' ? 'Diperiksa' : ($queue->status === 'completed' ? 'Selesai' : 'Batal'))) }}
                            </span>
                        </td>
                        <td>{{ $queue->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('queues.show', $queue) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            @if($queue->status === 'waiting')
                            <form action="{{ route('queues.call', $queue) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-primary" title="Panggil"><i class="bi bi-megaphone"></i></button>
                            </form>
                            @endif
                            @if(in_array($queue->status, ['called', 'in_progress']))
                            <form action="{{ route('queues.complete', $queue) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success" title="Selesai" onclick="return confirm('Selesaikan antrian ini?')"><i class="bi bi-check-lg"></i></button>
                            </form>
                            @endif
                            <a href="{{ route('queues.edit', $queue) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('queues.destroy', $queue) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus antrian ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada antrian</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $queues->links() }}
    </div>
</div>
@endsection
