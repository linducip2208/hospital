@extends('layouts.admin')

@section('title', 'Data Aset')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Data Aset</h1>
    <a href="{{ route('assets.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Aset</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row g-2">
                <div class="col-auto flex-grow-1">
                    <input type="text" name="search" class="form-control" placeholder="Cari kode, nama, supplier..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Kode Aset</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Departemen</th>
                        <th>Tgl Beli</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr>
                        <td><code>{{ $asset->asset_code }}</code></td>
                        <td><a href="{{ route('assets.show', $asset) }}" class="text-decoration-none">{{ Str::limit($asset->name, 30) }}</a></td>
                        <td>
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
                        </td>
                        <td>{{ $asset->department->name ?? '-' }}</td>
                        <td>{{ $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : '-' }}</td>
                        <td>
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
                        </td>
                        <td>
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
                        </td>
                        <td>
                            <a href="{{ route('assets.show', $asset) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('assets.edit', $asset) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus aset ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data aset</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $assets->links() }}
    </div>
</div>
@endsection
