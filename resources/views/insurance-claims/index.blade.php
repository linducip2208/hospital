@extends('layouts.admin')
@section('title','Klaim Asuransi')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Klaim Asuransi</h1>
    <a href="{{ route('insurance-claims.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat Klaim</a>
</div>
<div class="card shadow-sm"><div class="card-body">
<div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>No.</th><th>Pasien</th><th>Provider</th><th>Polis</th><th>Tipe</th><th>Diajukan</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($claims as $c)
<tr>
    <td><code>{{ $c->claim_no }}</code></td>
    <td>{{ $c->patient->name ?? '-' }}</td>
    <td>{{ $c->insurance_provider }}</td>
    <td>{{ $c->policy_number }}</td>
    <td>{{ $c->claim_type }}</td>
    <td>Rp {{ number_format((float) $c->claimed_amount,0,',','.') }}</td>
    <td><span class="badge bg-secondary">{{ $c->status }}</span></td>
    <td>
        <a href="{{ route('insurance-claims.print', $c) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-printer"></i></a>
        <a href="{{ route('insurance-claims.show', $c) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('insurance-claims.edit', $c) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('insurance-claims.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="8" class="text-center text-muted py-4">Belum ada klaim</td></tr>@endforelse
</tbody></table></div>
{{ $claims->links() }}
</div></div>
@endsection
