@extends('portal.layout')

@section('title', 'Tagihan')

@section('content')
<h1 class="h4 fw-bold mb-3">Tagihan Saya</h1>

<div class="card-soft p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>No. Invoice</th><th>Tanggal</th><th>Jumlah</th><th>Metode</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td class="fw-semibold">{{ $inv->invoice_number }}</td>
                    <td>{{ $inv->created_at?->format('d M Y') }}</td>
                    <td>Rp {{ number_format($inv->amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($inv->payment_method) }}</td>
                    <td><span class="badge bg-{{ $inv->status === 'completed' ? 'success' : ($inv->status === 'pending' ? 'warning' : 'secondary') }}">{{ $inv->status }}</span></td>
                    <td><a href="{{ route('portal.invoices.show', $inv) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada tagihan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $invoices->links() }}</div>
@endsection
