@extends('layouts.admin')

@section('title', 'Chart of Accounts')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Chart of Accounts</h1>
    <a href="{{ route('chart-of-accounts.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Akun</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-auto flex-grow-1">
                    <input type="text" name="search" class="form-control" placeholder="Cari kode atau nama akun..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Tipe Akun</th>
                        <th>Saldo Normal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $coa)
                    <tr>
                        <td><code>{{ $coa->account_code }}</code></td>
                        <td><a href="{{ route('chart-of-accounts.show', $coa) }}" class="text-decoration-none">{{ $coa->account_name }}</a></td>
                        <td>
                            <span class="badge bg-{{
                                $coa->account_type === 'asset' ? 'info' :
                                ($coa->account_type === 'liability' ? 'warning' :
                                ($coa->account_type === 'equity' ? 'primary' :
                                ($coa->account_type === 'revenue' ? 'success' : 'danger')))
                            }}">
                                @switch($coa->account_type)
                                    @case('asset') Aset @break
                                    @case('liability') Liabilitas @break
                                    @case('equity') Ekuitas @break
                                    @case('revenue') Pendapatan @break
                                    @case('expense') Beban @break
                                    @default {{ $coa->account_type }}
                                @endswitch
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $coa->normal_balance === 'debit' ? 'info' : 'warning' }}">
                                {{ $coa->normal_balance === 'debit' ? 'Debit' : 'Kredit' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $coa->is_active ? 'success' : 'secondary' }}">
                                {{ $coa->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('chart-of-accounts.show', $coa) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('chart-of-accounts.edit', $coa) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('chart-of-accounts.destroy', $coa) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus akun ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data chart of accounts</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $accounts->links() }}
    </div>
</div>
@endsection
