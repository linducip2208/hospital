@extends('layouts.admin')

@section('title', 'Purchase Order')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Purchase Order</h1>
    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah PO</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-auto flex-grow-1">
                    <input type="text" name="search" class="form-control" placeholder="Cari nomor PO, supplier..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('purchase-orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No. PO</th>
                        <th>Supplier</th>
                        <th>Tgl Order</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                    <tr>
                        <td><code>{{ $po->po_number }}</code></td>
                        <td><a href="{{ route('purchase-orders.show', $po) }}" class="text-decoration-none">{{ $po->supplier_name }}</a></td>
                        <td>{{ $po->order_date->format('d/m/Y') }}</td>
                        <td class="text-end">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{
                                $po->status === 'received' ? 'success' :
                                ($po->status === 'draft' ? 'warning' :
                                ($po->status === 'sent' ? 'info' : 'danger'))
                            }}">
                                @switch($po->status)
                                    @case('draft') Draft @break
                                    @case('sent') Terkirim @break
                                    @case('received') Diterima @break
                                    @case('cancelled') Dibatalkan @break
                                    @default {{ $po->status }}
                                @endswitch
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('purchase-orders.show', $po) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            @if($po->status === 'draft')
                            <a href="{{ route('purchase-orders.edit', $po) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            @endif
                            <form action="{{ route('purchase-orders.destroy', $po) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus purchase order ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data purchase order</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $purchaseOrders->links() }}
    </div>
</div>
@endsection
