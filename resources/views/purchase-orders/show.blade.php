@extends('layouts.admin')

@section('title', 'Detail Purchase Order')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Purchase Order</h1>
    <div>
        @if($purchaseOrder->status === 'draft')
        <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        @endif
        <a href="{{ route('purchase-orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2">No. PO</h6>
                <code class="fs-5">{{ $purchaseOrder->po_number }}</code>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2">Status</h6>
                <span class="badge bg-{{
                    $purchaseOrder->status === 'received' ? 'success' :
                    ($purchaseOrder->status === 'draft' ? 'warning' :
                    ($purchaseOrder->status === 'sent' ? 'info' : 'danger'))
                }} fs-6">
                    @switch($purchaseOrder->status)
                        @case('draft') Draft @break
                        @case('sent') Terkirim @break
                        @case('received') Diterima @break
                        @case('cancelled') Dibatalkan @break
                        @default {{ $purchaseOrder->status }}
                    @endswitch
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2">Tanggal Order</h6>
                <strong>{{ $purchaseOrder->order_date->format('d/m/Y') }}</strong>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total</h6>
                <strong class="fs-5">Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Supplier</h6>
                <p class="mb-0 fw-bold">{{ $purchaseOrder->supplier_name }}</p>
                @if($purchaseOrder->supplier_phone)
                    <small class="text-muted"><i class="bi bi-telephone"></i> {{ $purchaseOrder->supplier_phone }}</small>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Departemen</h6>
                <p class="mb-0">{{ $purchaseOrder->department->name ?? '-' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Diharapkan Tiba</h6>
                <p class="mb-0">{{ $purchaseOrder->expected_date ? $purchaseOrder->expected_date->format('d/m/Y') : '-' }}</p>
                @if($purchaseOrder->received_date)
                    <small class="text-success"><i class="bi bi-check-lg"></i> Diterima: {{ $purchaseOrder->received_date->format('d/m/Y') }}</small>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Item PO</span>
        @if($purchaseOrder->status === 'draft')
        <a href="#addItem" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Tambah Item</a>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Item</th>
                        <th>Qty</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-end">Total</th>
                        @if($purchaseOrder->status === 'draft')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrder->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                        @if($purchaseOrder->status === 'draft')
                        <td></td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="{{ $purchaseOrder->status === 'draft' ? 6 : 5 }}" class="text-center text-muted py-4">Belum ada item PO</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="{{ $purchaseOrder->status === 'draft' ? 4 : 3 }}" class="text-end fw-bold">Subtotal</td>
                        <td class="text-end">Rp {{ number_format($purchaseOrder->subtotal, 0, ',', '.') }}</td>
                        @if($purchaseOrder->status === 'draft')
                        <td></td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="{{ $purchaseOrder->status === 'draft' ? 4 : 3 }}" class="text-end">Pajak</td>
                        <td class="text-end">Rp {{ number_format($purchaseOrder->tax, 0, ',', '.') }}</td>
                        @if($purchaseOrder->status === 'draft')
                        <td></td>
                        @endif
                    </tr>
                    <tr class="fw-bold">
                        <td colspan="{{ $purchaseOrder->status === 'draft' ? 4 : 3 }}" class="text-end">Total</td>
                        <td class="text-end">Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
                        @if($purchaseOrder->status === 'draft')
                        <td></td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@if($purchaseOrder->notes)
<div class="card shadow-sm">
    <div class="card-header">Catatan</div>
    <div class="card-body">
        <p class="mb-0">{{ $purchaseOrder->notes }}</p>
    </div>
</div>
@endif
@endsection
