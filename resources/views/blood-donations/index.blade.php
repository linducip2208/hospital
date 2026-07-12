@extends('layouts.admin')

@section('title', 'Bank Darah')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Bank Darah</h1>
    <a href="{{ route('blood-donations.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Donor Baru</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <select name="blood_type" class="form-select">
                    <option value="">Semua Golongan Darah</option>
                    @foreach($bloodTypes as $bt)
                        <option value="{{ $bt }}" @selected(request('blood_type') === $bt)>{{ $bt }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="available" @selected(request('status') === 'available')>Available</option>
                    <option value="used" @selected(request('status') === 'used')>Used</option>
                    <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                    <option value="discarded" @selected(request('status') === 'discarded')>Discarded</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('blood_type') || request('status'))
                    <a href="{{ route('blood-donations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Donor</th><th>Gol. Darah</th><th>Tgl Donor</th><th>Tgl Kadaluarsa</th><th>Volume (ml)</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($donations as $donation)
                    <tr>
                        <td>{{ $loop->iteration + ($donations->currentPage() - 1) * $donations->perPage() }}</td>
                        <td><a href="{{ route('blood-donations.show', $donation) }}" class="text-decoration-none">{{ $donation->donor_name }}</a></td>
                        <td>
                            <span class="badge bg-danger">{{ $donation->blood_type }}</span>
                        </td>
                        <td>{{ $donation->donation_date ? $donation->donation_date->format('d/m/Y') : '-' }}</td>
                        <td>
                            @php
                                $expiredClass = $donation->expiry_date && $donation->expiry_date->isPast() ? 'text-danger fw-bold' : '';
                            @endphp
                            <span class="{{ $expiredClass }}">{{ $donation->expiry_date ? $donation->expiry_date->format('d/m/Y') : '-' }}</span>
                        </td>
                        <td>{{ $donation->quantity_ml ?? '-' }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'available' => 'success',
                                    'used' => 'secondary',
                                    'expired' => 'danger',
                                    'discarded' => 'dark',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$donation->status] ?? 'secondary' }}">
                                {{ ucfirst($donation->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('blood-donations.show', $donation) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('blood-donations.edit', $donation) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('blood-donations.destroy', $donation) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data donor ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data donor darah</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $donations->links() }}
    </div>
</div>
@endsection
