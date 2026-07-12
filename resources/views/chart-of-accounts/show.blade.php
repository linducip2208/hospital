@extends('layouts.admin')

@section('title', 'Detail Akun')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Akun</h1>
    <div>
        <a href="{{ route('chart-of-accounts.edit', $chartOfAccount) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="display-1 text-secondary mb-2">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <h5>{{ $chartOfAccount->account_name }}</h5>
                    <code class="fs-6">{{ $chartOfAccount->account_code }}</code>
                </div>
                <span class="badge bg-{{ $chartOfAccount->is_active ? 'success' : 'secondary' }}">
                    {{ $chartOfAccount->is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Tipe Akun:</strong>
                    <span class="badge bg-{{
                        $chartOfAccount->account_type === 'asset' ? 'info' :
                        ($chartOfAccount->account_type === 'liability' ? 'warning' :
                        ($chartOfAccount->account_type === 'equity' ? 'primary' :
                        ($chartOfAccount->account_type === 'revenue' ? 'success' : 'danger')))
                    }}">
                        @switch($chartOfAccount->account_type)
                            @case('asset') Aset @break
                            @case('liability') Liabilitas @break
                            @case('equity') Ekuitas @break
                            @case('revenue') Pendapatan @break
                            @case('expense') Beban @break
                            @default {{ $chartOfAccount->account_type }}
                        @endswitch
                    </span>
                </li>
                <li class="list-group-item">
                    <strong>Saldo Normal:</strong>
                    <span class="badge bg-{{ $chartOfAccount->normal_balance === 'debit' ? 'info' : 'warning' }}">
                        {{ $chartOfAccount->normal_balance === 'debit' ? 'Debit' : 'Kredit' }}
                    </span>
                </li>
                <li class="list-group-item"><strong>Akun Induk:</strong> {{ $chartOfAccount->parent ? $chartOfAccount->parent->account_code.' - '.$chartOfAccount->parent->account_name : '-' }}</li>
                @if($chartOfAccount->description)
                <li class="list-group-item"><strong>Deskripsi:</strong><br>{{ $chartOfAccount->description }}</li>
                @endif
            </ul>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card shadow-sm mb-3">
            <div class="card-header d-flex justify-content-between">
                <span>Akun Turunan ({{ $chartOfAccount->children->count() }})</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Kode Akun</th><th>Nama Akun</th><th>Tipe</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($chartOfAccount->children as $child)
                        <tr>
                            <td><code>{{ $child->account_code }}</code></td>
                            <td><a href="{{ route('chart-of-accounts.show', $child) }}" class="text-decoration-none">{{ $child->account_name }}</a></td>
                            <td>
                                <span class="badge bg-{{
                                    $child->account_type === 'asset' ? 'info' :
                                    ($child->account_type === 'liability' ? 'warning' :
                                    ($child->account_type === 'equity' ? 'primary' :
                                    ($child->account_type === 'revenue' ? 'success' : 'danger')))
                                }}">
                                    @switch($child->account_type)
                                        @case('asset') Aset @break
                                        @case('liability') Liabilitas @break
                                        @case('equity') Ekuitas @break
                                        @case('revenue') Pendapatan @break
                                        @case('expense') Beban @break
                                        @default {{ $child->account_type }}
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $child->is_active ? 'success' : 'secondary' }}">
                                    {{ $child->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Belum ada akun turunan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">Jurnal Terkait</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>No. Jurnal</th><th>Tanggal</th><th>Deskripsi</th><th>Debit</th><th>Kredit</th></tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="5" class="text-center text-muted py-3">Data jurnal akan tampil di sini</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
