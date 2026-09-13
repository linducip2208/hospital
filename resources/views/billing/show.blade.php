@extends('layouts.admin')

@section('title', 'Tagihan '.$bill->bill_no)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h1 class="h3 mb-1">Tagihan {{ $bill->bill_no }}</h1><p class="text-muted mb-0">{{ $bill->patient->name }} · encounter {{ $bill->encounter?->encounter_no ?? '-' }}</p></div>
    <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : 'warning' }}">{{ strtoupper($bill->status) }}</span>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card shadow-sm mb-4"><div class="card-body p-0"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Layanan</th><th class="text-end">Qty</th><th class="text-end">Jumlah</th></tr></thead><tbody>
@forelse($bill->items as $item)<tr><td>{{ $item->description }}</td><td class="text-end">{{ $item->quantity }}</td><td class="text-end">Rp {{ number_format((float) $item->amount, 0, ',', '.') }}</td></tr>@empty<tr><td colspan="3" class="text-center text-muted py-4">Belum ada charge.</td></tr>@endforelse
</tbody><tfoot><tr><th colspan="2">Tanggung jawab pasien</th><th class="text-end">Rp {{ number_format((float) $bill->patient_responsibility, 0, ',', '.') }}</th></tr><tr><th colspan="2">Dibayar / Sisa</th><th class="text-end">Rp {{ number_format((float) $bill->paid_amount, 0, ',', '.') }} / Rp {{ number_format((float) $bill->balance, 0, ',', '.') }}</th></tr></tfoot></table></div></div></div>
@if($bill->balance > 0 && !in_array($bill->status, ['cancelled','refunded']))
<div class="card shadow-sm"><div class="card-body"><h2 class="h5">Alokasi Pembayaran</h2><form method="POST" action="{{ route('billing.pay', $bill) }}" class="row g-3">@csrf<div class="col-md-4"><label class="form-label">Nominal</label><input name="amount" type="number" min="0.01" max="{{ $bill->balance }}" step="0.01" value="{{ $bill->balance }}" class="form-control" required></div><div class="col-md-4"><label class="form-label">Metode</label><select name="payment_method" class="form-select"><option value="cash">Tunai</option><option value="transfer">Transfer</option><option value="debit">Debit</option><option value="credit">Kartu Kredit</option><option value="qris">QRIS</option><option value="bpjs">BPJS</option><option value="insurance">Asuransi</option></select></div><div class="col-md-4 d-flex align-items-end"><button class="btn btn-primary w-100">Terima & Jurnal</button></div></form></div></div>
@endif
@endsection
