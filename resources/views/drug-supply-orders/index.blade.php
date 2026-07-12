@extends('layouts.admin')
@section('title', 'Surat Pesanan Obat')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Surat Pesanan Obat (SP)</h1>
    <a href="{{ route('drug-supply-orders.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Buat SP</a>
</div>
<div class="card shadow-sm"><div class="card-body">
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4"><select name="order_type" class="form-select"><option value="">Semua Jenis</option>@foreach($types as $k=>$l)<option value="{{ $k }}" @selected(request('order_type')===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-auto"><button class="btn btn-outline-primary"><i class="bi bi-filter"></i></button></div>
</form>
<div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>No.</th><th>Jenis</th><th>Tanggal</th><th>Supplier</th><th>Item</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($orders as $o)
<tr>
    <td><code>{{ $o->order_no }}</code></td>
    <td><span class="badge bg-{{ ['regular'=>'secondary','narcotic'=>'danger','psychotropic'=>'warning','precursor'=>'info'][$o->order_type] ?? 'secondary' }}">{{ $types[$o->order_type] ?? $o->order_type }}</span></td>
    <td>{{ $o->order_date?->format('d M Y') }}</td>
    <td>{{ $o->supplier_name }}</td>
    <td>{{ $o->items->count() }}</td>
    <td><span class="badge bg-{{ ['draft'=>'secondary','sent'=>'primary','received'=>'success','cancelled'=>'danger'][$o->status] ?? 'secondary' }}">{{ $o->status }}</span></td>
    <td>
        <a href="{{ route('drug-supply-orders.print', $o) }}" target="_blank" class="btn btn-sm btn-success"><i class="bi bi-printer"></i></a>
        <a href="{{ route('drug-supply-orders.show', $o) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('drug-supply-orders.edit', $o) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('drug-supply-orders.destroy', $o) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada SP</td></tr>@endforelse
</tbody></table></div>
{{ $orders->links() }}
</div></div>
@endsection
