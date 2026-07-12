@extends('layouts.admin')

@section('title', 'Detail Jurnal Entry')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Jurnal Entry</h1>
    <div>
        @if($journalEntry->status === 'draft')
        <a href="{{ route('journal-entries.edit', $journalEntry) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <form action="{{ route('journal-entries.post', $journalEntry) }}" method="POST" class="d-inline" onsubmit="return confirm('Posting jurnal ini? Setelah diposting, jurnal tidak dapat diubah.')">
            @csrf
            <button class="btn btn-success"><i class="bi bi-check-lg"></i> Posting</button>
        </form>
        @endif
        <a href="{{ route('journal-entries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2">No. Jurnal</h6>
                <code class="fs-5">{{ $journalEntry->journal_number }}</code>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2">Status</h6>
                <span class="badge bg-{{ $journalEntry->status === 'posted' ? 'success' : 'warning' }} fs-6">
                    {{ $journalEntry->status === 'posted' ? 'Diposting' : 'Draft' }}
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2">Tanggal</h6>
                <strong>{{ $journalEntry->entry_date->format('d/m/Y') }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Deskripsi</h6>
                <p class="mb-0">{{ $journalEntry->description }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Referensi</h6>
                <p class="mb-0">{{ $journalEntry->reference ?? '-' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Diposting Oleh</h6>
                <p class="mb-0">{{ $journalEntry->postedBy ? $journalEntry->postedBy->name : '-' }}</p>
                @if($journalEntry->posted_at)
                    <small class="text-muted">{{ $journalEntry->posted_at->format('d/m/Y H:i') }}</small>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Baris Jurnal</span>
        @if($journalEntry->status === 'draft')
        <a href="#addLine" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah Baris</a>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Akun</th>
                        <th>Deskripsi</th>
                        <th class="text-end">Debit</th>
                        <th class="text-end">Kredit</th>
                        @if($journalEntry->status === 'draft')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($journalEntry->lines as $line)
                    <tr>
                        <td>{{ $line->chartOfAccount->account_code ?? '-' }} - {{ $line->chartOfAccount->account_name ?? '-' }}</td>
                        <td>{{ $line->description ?? '-' }}</td>
                        <td class="text-end">Rp {{ number_format($line->debit, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($line->credit, 0, ',', '.') }}</td>
                        @if($journalEntry->status === 'draft')
                        <td></td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="{{ $journalEntry->status === 'draft' ? 5 : 4 }}" class="text-center text-muted py-4">Belum ada baris jurnal</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td colspan="2" class="text-end">Total</td>
                        <td class="text-end">Rp {{ number_format($journalEntry->total_debit, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($journalEntry->total_credit, 0, ',', '.') }}</td>
                        @if($journalEntry->status === 'draft')
                        <td></td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@if($journalEntry->notes)
<div class="card shadow-sm">
    <div class="card-header">Catatan</div>
    <div class="card-body">
        <p class="mb-0">{{ $journalEntry->notes }}</p>
    </div>
</div>
@endif
@endsection
