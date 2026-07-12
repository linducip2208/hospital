@extends('layouts.admin')

@section('title', 'Log Aktivitas / Audit Trail')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2"><i class="bi bi-clock-history"></i> Log Aktivitas / Audit Trail</h1>
    <span class="text-muted">{{ $logs->total() }} aktivitas tercatat</span>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari deskripsi / user..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="event" class="form-select">
                    <option value="">Semua Aksi</option>
                    @foreach($events as $ev)
                        <option value="{{ $ev }}" @selected(request('event') === $ev)>{{ ucfirst($ev) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary w-100" type="submit"><i class="bi bi-funnel"></i> Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Waktu</th><th>Pengguna</th><th>Aksi</th><th>Kategori</th><th>Deskripsi</th><th>IP</th></tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="small text-muted">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $log->user_name }}</td>
                        <td><span class="badge bg-{{ $log->badgeColor() }}">{{ ucfirst($log->event) }}</span></td>
                        <td><span class="badge bg-light text-dark">{{ ucfirst($log->category) }}</span></td>
                        <td>{{ $log->description }}</td>
                        <td class="small text-muted">{{ $log->ip_address }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada aktivitas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $logs->links() }}
    </div>
</div>
@endsection
