@extends('layouts.admin')

@section('title', 'Jurnal Entry')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Jurnal Entry</h1>
    <a href="{{ route('journal-entries.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Jurnal</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-auto flex-grow-1">
                    <input type="text" name="search" class="form-control" placeholder="Cari nomor atau deskripsi..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('journal-entries.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No. Jurnal</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Total Debit</th>
                        <th>Total Kredit</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($journalEntries as $je)
                    <tr>
                        <td><code>{{ $je->journal_number }}</code></td>
                        <td>{{ $je->entry_date->format('d/m/Y') }}</td>
                        <td>{{ Str::limit($je->description, 40) }}</td>
                        <td class="text-end">Rp {{ number_format($je->total_debit, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($je->total_credit, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $je->status === 'posted' ? 'success' : 'warning' }}">
                                {{ $je->status === 'posted' ? 'Diposting' : 'Draft' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('journal-entries.show', $je) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            @if($je->status === 'draft')
                            <a href="{{ route('journal-entries.edit', $je) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            @endif
                            <form action="{{ route('journal-entries.destroy', $je) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jurnal ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data jurnal entry</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $journalEntries->links() }}
    </div>
</div>
@endsection
