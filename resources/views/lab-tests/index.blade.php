@extends('layouts.admin')

@section('title', 'Laboratorium')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Laboratorium</h1>
    <a href="{{ route('lab-tests.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Pemeriksaan Baru</a>
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
                    <option value="requested" @selected(request('status') === 'requested')>Requested</option>
                    <option value="sample_collected" @selected(request('status') === 'sample_collected')>Sample Collected</option>
                    <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="test_type" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="Hematologi" @selected(request('test_type') === 'Hematologi')>Hematologi</option>
                    <option value="Kimia Darah" @selected(request('test_type') === 'Kimia Darah')>Kimia Darah</option>
                    <option value="Urinalisis" @selected(request('test_type') === 'Urinalisis')>Urinalisis</option>
                    <option value="Serologi" @selected(request('test_type') === 'Serologi')>Serologi</option>
                    <option value="Mikrobiologi" @selected(request('test_type') === 'Mikrobiologi')>Mikrobiologi</option>
                    <option value="Imunologi" @selected(request('test_type') === 'Imunologi')>Imunologi</option>
                    <option value="Lainnya" @selected(request('test_type') === 'Lainnya')>Lainnya</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('search') || request('status') || request('test_type'))
                    <a href="{{ route('lab-tests.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Pasien</th><th>Dokter</th><th>Pemeriksaan</th><th>Tipe</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($labTests as $labTest)
                    <tr>
                        <td>{{ $loop->iteration + ($labTests->currentPage() - 1) * $labTests->perPage() }}</td>
                        <td><a href="{{ route('lab-tests.show', $labTest) }}" class="text-decoration-none">{{ $labTest->patient->name ?? '-' }}</a></td>
                        <td>{{ $labTest->doctor->name ?? '-' }}</td>
                        <td>{{ $labTest->test_name }}</td>
                        <td>{{ $labTest->test_type ?? '-' }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'requested' => 'secondary',
                                    'sample_collected' => 'info',
                                    'in_progress' => 'warning',
                                    'completed' => 'success',
                                    'cancelled' => 'danger',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$labTest->status] ?? 'secondary' }}">
                                {{ str_replace('_', ' ', ucfirst($labTest->status)) }}
                            </span>
                        </td>
                        <td>{{ $labTest->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('lab-tests.show', $labTest) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('lab-tests.edit', $labTest) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('lab-tests.destroy', $labTest) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pemeriksaan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data pemeriksaan lab</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $labTests->links() }}
    </div>
</div>
@endsection
