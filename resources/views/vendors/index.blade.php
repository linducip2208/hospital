@extends('layouts.admin')

@section('title', 'Vendor / Supplier')

@section('content')
<div class="d-flex justify-content-between flex-wrap align-items-center page-header">
    <h1 class="h2"><i class="bi bi-shop"></i> Vendor / Supplier</h1>
    <a href="{{ route('vendors.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Vendor</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col"><input type="text" name="search" class="form-control" placeholder="Cari vendor..." value="{{ request('search') }}"></div>
            <div class="col-auto"><button class="btn btn-outline-primary"><i class="bi bi-search"></i></button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Kontak</th><th>PO</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($vendors as $v)
                    <tr>
                        <td><code>{{ $v->code }}</code></td>
                        <td>{{ $v->name }}</td>
                        <td>{{ $v->category ?? '-' }}</td>
                        <td class="small">{{ $v->contact_person ?? '-' }}<br>{{ $v->phone }}</td>
                        <td>{{ $v->purchase_orders_count }}</td>
                        <td><span class="badge bg-{{ $v->is_active ? 'success' : 'secondary' }}">{{ $v->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <a href="{{ route('vendors.edit', $v) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('vendors.destroy', $v) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus vendor?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada vendor</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $vendors->links() }}
    </div>
</div>
@endsection
