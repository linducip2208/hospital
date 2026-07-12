@extends('layouts.admin')

@section('title', 'Perawat & Bidan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Perawat & Bidan</h1>
    <a href="{{ route('nurse-assignments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Tugas</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-2">
                <select name="user_id" class="form-select">
                    <option value="">Semua Petugas</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="assignment_type" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="nurse" @selected(request('assignment_type') === 'nurse')>Perawat</option>
                    <option value="midwife" @selected(request('assignment_type') === 'midwife')>Bidan</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('user_id') || request('assignment_type') || request('status'))
                    <a href="{{ route('nurse-assignments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Pasien</th><th>Petugas</th><th>Role</th><th>Tugas</th><th>Shift</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                    <tr>
                        <td>{{ $loop->iteration + ($assignments->currentPage() - 1) * $assignments->perPage() }}</td>
                        <td><a href="{{ route('nurse-assignments.show', $assignment) }}" class="text-decoration-none">{{ $assignment->patient->name ?? '-' }}</a></td>
                        <td>{{ $assignment->user->name ?? '-' }}</td>
                        <td>{{ $assignment->assignment_type === 'midwife' ? 'Bidan' : 'Perawat' }}</td>
                        <td>{{ Str::limit($assignment->task_description, 40) }}</td>
                        <td>{{ ucfirst($assignment->shift) }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'in_progress' => 'info',
                                    'completed' => 'success',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$assignment->status] ?? 'secondary' }}">
                                {{ str_replace('_', ' ', ucfirst($assignment->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('nurse-assignments.show', $assignment) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('nurse-assignments.edit', $assignment) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('nurse-assignments.destroy', $assignment) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus tugas ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada tugas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $assignments->links() }}
    </div>
</div>
@endsection
