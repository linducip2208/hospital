@extends('layouts.admin')

@section('title', 'Detail Kamar')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Kamar</h1>
    <div>
        <a href="{{ route('rooms.edit', $room) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-building"></i></div>
                <h5>Kamar {{ $room->room_number }}</h5>
                <p class="text-muted mb-1">{{ $room->room_type ?? '-' }}</p>
                <span class="badge bg-{{ $room->status === 'available' ? 'success' : ($room->status === 'occupied' ? 'warning' : 'danger') }}">
                    {{ $room->status === 'available' ? 'Tersedia' : ($room->status === 'occupied' ? 'Terisi' : 'Perbaikan') }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Lantai:</strong> {{ $room->floor ?? '-' }}</li>
                <li class="list-group-item"><strong>Tempat Tidur:</strong> {{ $room->bed_count ?? 0 }}</li>
                <li class="list-group-item"><strong>Harga/Hari:</strong> Rp {{ number_format($room->price_per_day ?? 0, 0, ',', '.') }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Detail Kamar</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <tbody>
                        <tr><td style="width:200px"><strong>Nomor Kamar</strong></td><td>{{ $room->room_number }}</td></tr>
                        <tr><td><strong>Tipe Kamar</strong></td><td>{{ $room->room_type ?? '-' }}</td></tr>
                        <tr><td><strong>Lantai</strong></td><td>{{ $room->floor ?? '-' }}</td></tr>
                        <tr><td><strong>Jumlah Tempat Tidur</strong></td><td>{{ $room->bed_count ?? 0 }}</td></tr>
                        <tr><td><strong>Harga/Hari</strong></td><td>Rp {{ number_format($room->price_per_day ?? 0, 0, ',', '.') }}</td></tr>
                        <tr><td><strong>Status</strong></td>
                            <td>
                                <span class="badge bg-{{ $room->status === 'available' ? 'success' : ($room->status === 'occupied' ? 'warning' : 'danger') }}">
                                    {{ $room->status === 'available' ? 'Tersedia' : ($room->status === 'occupied' ? 'Terisi' : 'Perbaikan') }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if($room->facilities)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Fasilitas</div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $facilitiesList = is_array($room->facilities) ? $room->facilities : explode("\n", $room->facilities);
                    @endphp
                    @foreach($facilitiesList as $facility)
                        @if(trim($facility))
                            <span class="badge bg-info text-dark fs-6">{{ trim($facility) }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if($room->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p>{{ $room->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
