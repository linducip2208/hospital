@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Pembayaran</h1>
    <div>
        <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th style="width:180px">Pasien</th><td>{{ $payment->appointment?->patient?->name ?? '-' }}</td></tr>
            <tr><th>Appointment</th><td>{{ $payment->appointment?->appointment_date?->format('d/m/Y H:i') ?? '-' }}</td></tr>
            <tr><th>Dokter</th><td>{{ $payment->appointment?->doctor?->name ?? '-' }}</td></tr>
            <tr><th>Treatment</th><td>{{ $payment->appointment?->treatment?->name ?? '-' }}</td></tr>
            <tr><th>Jumlah</th><td><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td></tr>
            <tr><th>Metode</th><td>{{ $payment->payment_method ?? '-' }}</td></tr>
            <tr><th>Status</th>
                <td>
                    <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : ($payment->status === 'cancelled' ? 'danger' : 'info')) }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </td>
            </tr>
            <tr><th>Tanggal</th><td>{{ $payment->created_at->format('d/m/Y H:i') }}</td></tr>
            @if($payment->notes)
            <tr><th>Catatan</th><td>{{ $payment->notes }}</td></tr>
            @endif
        </table>
        @if($payment->status === 'completed')
        <form method="POST" action="{{ route('payments.refund',$payment) }}" class="row g-2 mt-3">@csrf<div class="col-md-4"><input type="number" name="amount" class="form-control" min="0.01" max="{{ $payment->paid_amount }}" step="0.01" placeholder="Nominal refund" required></div><div class="col-md-5"><input name="reason" class="form-control" placeholder="Alasan refund" required></div><div class="col-md-3"><button class="btn btn-outline-danger w-100">Proses Refund</button></div></form>
        @endif
    </div>
</div>
@endsection
