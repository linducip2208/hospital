@extends('layouts.admin')

@section('title', 'Rujukan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Rujukan</h1>
    <a href="{{ route('referrals.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Rujukan</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-filter"></i> Filter</button>
                @if(request('status'))
                    <a href="{{ route('referrals.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>#</th><th>Pasien</th><th>Dari Poli</th><th>Ke Poli</th><th>Dokter</th><th>Alasan</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($referrals as $referral)
                    <tr>
                        <td>{{ $loop->iteration + ($referrals->currentPage() - 1) * $referrals->perPage() }}</td>
                        <td><a href="{{ route('referrals.show', $referral) }}" class="text-decoration-none">{{ $referral->patient->name ?? '-' }}</a></td>
                        <td>{{ $referral->fromPolyclinic->name ?? '-' }}</td>
                        <td>{{ $referral->toPolyclinic->name ?? '-' }}</td>
                        <td>{{ $referral->doctor->name ?? '-' }}</td>
                        <td>{{ Str::limit($referral->reason, 30) }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'completed' => 'info',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$referral->status] ?? 'secondary' }}">
                                {{ ucfirst($referral->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('referrals.show', $referral) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('referrals.edit', $referral) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('referrals.destroy', $referral) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus rujukan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data rujukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $referrals->links() }}
    </div>
</div>
@endsection
