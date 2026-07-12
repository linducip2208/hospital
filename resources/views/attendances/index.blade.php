@extends('layouts.admin')

@section('title', 'Data Absensi')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Data Absensi</h1>
    <a href="{{ route('attendances.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Absensi</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="user_id" class="form-select">
                    <option value="">Semua Pengguna</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="present" @selected(request('status') === 'present')>Hadir</option>
                    <option value="late" @selected(request('status') === 'late')>Terlambat</option>
                    <option value="sick" @selected(request('status') === 'sick')>Sakit</option>
                    <option value="leave" @selected(request('status') === 'leave')>Izin</option>
                    <option value="absent" @selected(request('status') === 'absent')>Alfa</option>
                    <option value="half_day" @selected(request('status') === 'half_day')>Setengah Hari</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" placeholder="Dari" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" placeholder="Sampai" value="{{ request('date_to') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('user_id') || request('status') || request('date_from') || request('date_to'))
                    <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}</td>
                        <td><a href="{{ route('attendances.show', $attendance) }}" class="text-decoration-none">{{ $attendance->user->name ?? '-' }}</a></td>
                        <td>{{ $attendance->date ? $attendance->date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $attendance->check_in ?? '-' }}</td>
                        <td>{{ $attendance->check_out ?? '-' }}</td>
                        <td>
                            @php
                                $attendanceColors = ['present' => 'success', 'late' => 'warning', 'sick' => 'info', 'leave' => 'primary', 'absent' => 'danger', 'half_day' => 'secondary'];
                                $attendanceLabels = ['present' => 'Hadir', 'late' => 'Terlambat', 'sick' => 'Sakit', 'leave' => 'Izin', 'absent' => 'Alfa', 'half_day' => 'Setengah Hari'];
                            @endphp
                            <span class="badge bg-{{ $attendanceColors[$attendance->status] ?? 'secondary' }}">
                                {{ $attendanceLabels[$attendance->status] ?? $attendance->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('attendances.show', $attendance) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus absensi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data absensi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $attendances->links() }}
    </div>
</div>
@endsection
