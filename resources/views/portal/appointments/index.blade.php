@extends('portal.layout')

@section('title', 'Janji Temu')

@section('content')
<h1 class="h4 fw-bold mb-3">Janji Temu Saya</h1>

<div class="card-soft p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Tanggal</th><th>Dokter</th><th>Poli</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($appointments as $appt)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $appt->appointment_date?->format('d M Y') }}</div>
                        <div class="small text-muted">{{ $appt->start_time }}</div>
                    </td>
                    <td>dr. {{ $appt->doctor?->name ?? '-' }}</td>
                    <td>{{ $appt->polyclinic?->name ?? '-' }}</td>
                    <td><span class="badge bg-{{ in_array($appt->status, ['completed','confirmed']) ? 'success' : ($appt->status === 'cancelled' || $appt->status === 'no_show' ? 'danger' : 'warning') }}">{{ $appt->status }}</span></td>
                    <td><a href="{{ route('portal.appointments.show', $appt) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada janji temu</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $appointments->links() }}</div>
@endsection
