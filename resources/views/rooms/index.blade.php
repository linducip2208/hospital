@extends('layouts.admin')

@section('title', 'Rawat Inap')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Rawat Inap</h1>
    <a href="{{ route('rooms.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Kamar</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Cari nomor kamar..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="available" @selected(request('status') === 'available')>Tersedia</option>
                    <option value="occupied" @selected(request('status') === 'occupied')>Terisi</option>
                    <option value="maintenance" @selected(request('status') === 'maintenance')>Perbaikan</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('status'))
                    <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>No Kamar</th><th>Tipe</th><th>Lantai</th><th>TT</th><th>Harga/Hari</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                    <tr>
                        <td>{{ $loop->iteration + ($rooms->currentPage() - 1) * $rooms->perPage() }}</td>
                        <td><a href="{{ route('rooms.show', $room) }}" class="text-decoration-none">{{ $room->room_number }}</a></td>
                        <td>{{ $room->room_type ?? '-' }}</td>
                        <td>{{ $room->floor ?? '-' }}</td>
                        <td>{{ $room->bed_count ?? 0 }}</td>
                        <td>Rp {{ number_format($room->price_per_day ?? 0, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $room->status === 'available' ? 'success' : ($room->status === 'occupied' ? 'warning' : 'danger') }}">
                                {{ $room->status === 'available' ? 'Tersedia' : ($room->status === 'occupied' ? 'Terisi' : 'Perbaikan') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('rooms.show', $room) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kamar ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data kamar</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $rooms->links() }}
    </div>
</div>
@endsection
