@extends('layouts.admin')

@section('title', 'Data Cuti')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Data Cuti</h1>
    <a href="{{ route('leaves.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Cuti</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="user_id" class="form-select">
                    <option value="">Semua Pengguna</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="leave_type" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="tahunan" @selected(request('leave_type') === 'tahunan')>Tahunan</option>
                    <option value="sakit" @selected(request('leave_type') === 'sakit')>Sakit</option>
                    <option value="melahirkan" @selected(request('leave_type') === 'melahirkan')>Melahirkan</option>
                    <option value="alasan_penting" @selected(request('leave_type') === 'alasan_penting')>Alasan Penting</option>
                    <option value="cuti_besar" @selected(request('leave_type') === 'cuti_besar')>Cuti Besar</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('user_id') || request('leave_type') || request('status'))
                    <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Jenis Cuti</th>
                        <th>Tgl Mulai</th>
                        <th>Tgl Selesai</th>
                        <th>Total Hari</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr>
                        <td>{{ $loop->iteration + ($leaves->currentPage() - 1) * $leaves->perPage() }}</td>
                        <td><a href="{{ route('leaves.show', $leave) }}" class="text-decoration-none">{{ $leave->user->name ?? '-' }}</a></td>
                        <td>
                            @php
                                $leaveTypeLabels = ['tahunan' => 'Tahunan', 'sakit' => 'Sakit', 'melahirkan' => 'Melahirkan', 'alasan_penting' => 'Alasan Penting', 'cuti_besar' => 'Cuti Besar'];
                            @endphp
                            {{ $leaveTypeLabels[$leave->leave_type] ?? $leave->leave_type }}
                        </td>
                        <td>{{ $leave->start_date ? $leave->start_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $leave->end_date ? $leave->end_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $leave->total_days ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $leave->status === 'approved' ? 'success' : ($leave->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ $leave->status === 'approved' ? 'Disetujui' : ($leave->status === 'rejected' ? 'Ditolak' : 'Pending') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('leaves.edit', $leave) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('leaves.destroy', $leave) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus cuti ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data cuti</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $leaves->links() }}
    </div>
</div>
@endsection
