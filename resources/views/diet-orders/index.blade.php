@extends('layouts.admin')
@section('title','Order Diet')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header"><h1 class="h2">Order Diet Pasien</h1><a href="{{ route('diet-orders.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Order Baru</a></div>
<div class="card shadow-sm"><div class="card-body"><div class="table-responsive"><table class="table table-hover">
<thead class="table-light"><tr><th>No.</th><th>Pasien</th><th>Diet</th><th>Mulai</th><th>Selesai</th><th>Status</th><th>Aksi</th></tr></thead>
<tbody>
@forelse($orders as $o)
<tr>
    <td><code>{{ $o->order_no }}</code></td>
    <td>{{ $o->patient->name ?? '-' }}</td>
    <td>{{ $types[$o->diet_type] ?? $o->diet_type }} @if($o->calories) ({{ $o->calories }} kcal)@endif</td>
    <td>{{ $o->start_date?->format('d M Y') }}</td>
    <td>{{ $o->end_date?->format('d M Y') ?? '-' }}</td>
    <td><span class="badge bg-{{ ['active'=>'success','paused'=>'warning','discontinued'=>'danger','completed'=>'info'][$o->status] }}">{{ $o->status }}</span></td>
    <td>
        <a href="{{ route('diet-orders.show', $o) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
        <a href="{{ route('diet-orders.edit', $o) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
        <form action="{{ route('diet-orders.destroy', $o) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
    </td>
</tr>
@empty<tr><td colspan="7" class="text-center text-muted py-4">Belum ada</td></tr>@endforelse
</tbody></table></div>{{ $orders->links() }}</div></div>
@endsection
