@extends('layouts.admin')

@section('title', 'Detail Aset')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Aset</h1>
    <div>
        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h5>{{ $asset->name }}</h5>
                <code>{{ $asset->asset_code }}</code>
                <div class="mt-2">
                    <span class="badge bg-{{
                        $asset->status === 'active' ? 'success' :
                        ($asset->status === 'maintenance' ? 'warning' : 'danger')
                    }}">
                        @switch($asset->status)
                            @case('active') Aktif @break
                            @case('maintenance') Perbaikan @break
                            @case('disposed') Dihapus @break
                            @default {{ $asset->status }}
                        @endswitch
                    </span>
                </div>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Kategori:</strong>
                    <span class="badge bg-info">
                        @switch($asset->category)
                            @case('medical') Medis @break
                            @case('IT') IT @break
                            @case('furniture') Furniture @break
                            @case('vehicle') Kendaraan @break
                            @case('building') Bangunan @break
                            @case('other') Lainnya @break
                            @default {{ $asset->category }}
                        @endswitch
                    </span>
                </li>
                <li class="list-group-item"><strong>Departemen:</strong> {{ $asset->department->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Lokasi:</strong> {{ $asset->location ?? '-' }}</li>
                <li class="list-group-item">
                    <strong>Kondisi:</strong>
                    <span class="badge bg-{{
                        $asset->condition === 'good' ? 'success' :
                        ($asset->condition === 'fair' ? 'warning' :
                        ($asset->condition === 'poor' ? 'warning' : 'danger'))
                    }}">
                        @switch($asset->condition)
                            @case('good') Baik @break
                            @case('fair') Cukup @break
                            @case('poor') Buruk @break
                            @case('damaged') Rusak @break
                            @default {{ $asset->condition }}
                        @endswitch
                    </span>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Informasi Pembelian</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-4">
                        <strong>Tanggal Beli:</strong><br>
                        {{ $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : '-' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Harga Beli:</strong><br>
                        {{ $asset->purchase_price ? 'Rp '.number_format($asset->purchase_price, 0, ',', '.') : '-' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Supplier:</strong><br>
                        {{ $asset->supplier ?? '-' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Serial Number:</strong><br>
                        {{ $asset->serial_number ?? '-' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Garansi Sampai:</strong><br>
                        {{ $asset->warranty_expiry ? $asset->warranty_expiry->format('d/m/Y') : '-' }}
                    </div>
                </div>
            </div>
        </div>

        @if($asset->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $asset->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
