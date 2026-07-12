@extends('layouts.admin')

@section('title', 'Jadwal Staff')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Jadwal Staff</h1>
    <a href="{{ route('staff-schedules.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Jadwal Baru</a>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="user_id" class="form-select">
                    <option value="">Semua Staff</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="shift_date" class="form-control" value="{{ request('shift_date') }}">
            </div>
            <div class="col-md-3">
                <select name="department" class="form-select">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" @selected(request('department') === $dept)>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('user_id') || request('shift_date') || request('department'))
                    <a href="{{ route('staff-schedules.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        @php
            $shiftTypeLabels = [
                'morning' => 'Pagi',
                'afternoon' => 'Siang',
                'night' => 'Malam',
                'on_call' => 'On Call',
                'off' => 'Off',
            ];
            $shiftTypeColors = [
                'morning' => 'warning',
                'afternoon' => 'info',
                'night' => 'dark',
                'on_call' => 'danger',
                'off' => 'secondary',
            ];
            $grouped = $schedules->groupBy(fn($s) => $s->shift_date->format('Y-m-d'));
        @endphp

        @forelse($grouped as $date => $items)
        <div class="mb-4">
            <h5 class="border-bottom pb-2 mb-3">
                <i class="bi bi-calendar-date me-2"></i>
                {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                <span class="badge bg-primary ms-2">{{ $items->count() }} staff</span>
            </h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Staff</th>
                            <th>Departemen</th>
                            <th>Shift</th>
                            <th>Jam</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $schedule)
                        <tr>
                            <td><a href="{{ route('staff-schedules.show', $schedule) }}" class="text-decoration-none">{{ $schedule->user->name ?? '-' }}</a></td>
                            <td>{{ $schedule->department ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $shiftTypeColors[$schedule->shift_type] ?? 'secondary' }}">
                                    {{ $shiftTypeLabels[$schedule->shift_type] ?? ucfirst($schedule->shift_type) }}
                                </span>
                            </td>
                            <td>
                                @if($schedule->start_time && $schedule->end_time)
                                    {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ Str::limit($schedule->notes, 30) }}</td>
                            <td>
                                <a href="{{ route('staff-schedules.show', $schedule) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('staff-schedules.edit', $schedule) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('staff-schedules.destroy', $schedule) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <p class="text-center text-muted py-4">Belum ada jadwal staff</p>
        @endforelse

        {{ $schedules->links() }}
    </div>
</div>
@endsection
