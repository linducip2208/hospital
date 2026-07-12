@extends('layouts.admin')

@section('title', 'Data Penggajian')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Data Penggajian</h1>
    <a href="{{ route('salaries.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Penggajian</a>
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
                <select name="period_month" class="form-select">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" @selected(request('period_month') == $m)>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="period_year" class="form-select">
                    <option value="">Semua Tahun</option>
                    @foreach(range(date('Y'), 2020) as $y)
                        <option value="{{ $y }}" @selected(request('period_year') == $y)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                    <option value="paid" @selected(request('status') === 'paid')>Dibayar</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                @if(request('user_id') || request('period_month') || request('period_year') || request('status'))
                    <a href="{{ route('salaries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Pengguna/Pegawai</th>
                        <th>Periode</th>
                        <th>Gaji Pokok</th>
                        <th>Total Gaji</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaries as $salary)
                    <tr>
                        <td>{{ $loop->iteration + ($salaries->currentPage() - 1) * $salaries->perPage() }}</td>
                        <td>
                            <a href="{{ route('salaries.show', $salary) }}" class="text-decoration-none">
                                {{ $salary->user->name ?? $salary->employee->user->name ?? '-' }}
                            </a>
                        </td>
                        <td>{{ $salary->period_month }}/{{ $salary->period_year }}</td>
                        <td>Rp {{ number_format($salary->base_salary ?? 0, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($salary->total_salary ?? 0, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $salary->status === 'paid' ? 'success' : ($salary->status === 'approved' ? 'info' : 'warning') }}">
                                {{ $salary->status === 'paid' ? 'Dibayar' : ($salary->status === 'approved' ? 'Disetujui' : 'Draft') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('salaries.show', $salary) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('salaries.edit', $salary) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('salaries.destroy', $salary) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus penggajian ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data penggajian</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $salaries->links() }}
    </div>
</div>
@endsection
