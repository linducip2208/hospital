@extends('layouts.admin')

@section('title', 'Detail Tugas')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Tugas</h1>
    <div>
        <a href="{{ route('nurse-assignments.edit', $nurseAssignment) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('nurse-assignments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-clipboard2-heart"></i></div>
                <h5>{{ $nurseAssignment->user->name ?? '-' }}</h5>
                @php
                    $statusColors = [
                        'pending' => 'warning',
                        'in_progress' => 'info',
                        'completed' => 'success',
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$nurseAssignment->status] ?? 'secondary' }}">
                    {{ str_replace('_', ' ', ucfirst($nurseAssignment->status)) }}
                </span>
                <p class="text-muted mt-2 mb-0">{{ $nurseAssignment->assignment_type === 'midwife' ? 'Bidan' : 'Perawat' }}</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Pasien:</strong> {{ $nurseAssignment->patient->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Shift:</strong> {{ ucfirst($nurseAssignment->shift) }}</li>
                <li class="list-group-item"><strong>Dibuat:</strong> {{ $nurseAssignment->created_at->format('d/m/Y H:i') }}</li>
                <li class="list-group-item"><strong>Diperbarui:</strong> {{ $nurseAssignment->updated_at->format('d/m/Y H:i') }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Deskripsi Tugas</div>
            <div class="card-body">
                <p class="mb-0">{{ $nurseAssignment->task_description }}</p>
            </div>
        </div>

        @if($nurseAssignment->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $nurseAssignment->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
