@extends('layouts.admin')

@section('title', 'IGD / Emergency')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">IGD / Emergency</h1>
    <a href="{{ route('emergencies.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Pasien IGD Baru</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari pasien..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="waiting" @selected(request('status') === 'waiting')>Waiting</option>
                    <option value="in_treatment" @selected(request('status') === 'in_treatment')>In Treatment</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="referred" @selected(request('status') === 'referred')>Referred</option>
                    <option value="deceased" @selected(request('status') === 'deceased')>Deceased</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="triage" class="form-select">
                    <option value="">Semua Triase</option>
                    <option value="red" @selected(request('triage') === 'red')>Red (CRITICAL)</option>
                    <option value="yellow" @selected(request('triage') === 'yellow')>Yellow (URGENT)</option>
                    <option value="green" @selected(request('triage') === 'green')>Green (NON-URGENT)</option>
                    <option value="black" @selected(request('triage') === 'black')>Black (DECEASED)</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('status') || request('triage'))
                    <a href="{{ route('emergencies.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Pasien</th><th>Triase</th><th>Keluhan</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($emergencies as $emergency)
                    <tr>
                        <td>{{ $loop->iteration + ($emergencies->currentPage() - 1) * $emergencies->perPage() }}</td>
                        <td><a href="{{ route('emergencies.show', $emergency) }}" class="text-decoration-none">{{ $emergency->patient->name ?? '-' }}</a></td>
                        <td>
                            @php
                                $triageColors = [
                                    'red' => 'danger',
                                    'yellow' => 'warning',
                                    'green' => 'success',
                                    'black' => 'dark',
                                ];
                                $triageLabels = [
                                    'red' => 'CRITICAL',
                                    'yellow' => 'URGENT',
                                    'green' => 'NON-URGENT',
                                    'black' => 'DECEASED',
                                ];
                            @endphp
                            <span class="badge bg-{{ $triageColors[$emergency->triage] ?? 'secondary' }}">
                                {{ $triageLabels[$emergency->triage] ?? ucfirst($emergency->triage) }}
                            </span>
                        </td>
                        <td>{{ Str::limit($emergency->complaint, 40) }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'waiting' => 'secondary',
                                    'in_treatment' => 'warning',
                                    'completed' => 'success',
                                    'referred' => 'info',
                                    'deceased' => 'dark',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$emergency->status] ?? 'secondary' }}">
                                {{ str_replace('_', ' ', ucfirst($emergency->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('emergencies.show', $emergency) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('emergencies.edit', $emergency) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('emergencies.destroy', $emergency) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data IGD ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data pasien IGD</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $emergencies->links() }}
    </div>
</div>
@endsection
