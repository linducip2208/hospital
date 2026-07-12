@extends('layouts.print')

@section('title', 'Laporan Keuangan')
@section('content')

<div class="doc-title">LAPORAN KEUANGAN &amp; OPERASIONAL</div>
<div class="doc-no">Periode: {{ $from->translatedFormat('d F Y') }} — {{ $to->translatedFormat('d F Y') }}</div>

<table class="meta-table" style="margin-bottom:14px">
    <tr><td>Total Pasien</td><td>: {{ number_format($stats['total_patients']) }}</td><td>Total Dokter</td><td>: {{ $stats['total_doctors'] }}</td></tr>
    <tr><td>Total Appointment</td><td>: {{ number_format($stats['total_appointments']) }}</td><td>Total Obat</td><td>: {{ $stats['total_drugs'] }}</td></tr>
    <tr><td>Total Pendapatan</td><td>: Rp {{ number_format($stats['total_payments'], 0, ',', '.') }}</td><td>Total Kamar</td><td>: {{ $stats['total_rooms'] }}</td></tr>
</table>

<h3>Pendapatan per {{ ['day' => 'Hari', 'month' => 'Bulan', 'year' => 'Tahun'][$groupBy] }}</h3>
<table class="data-table" style="margin-bottom:16px">
    <thead><tr><th>Periode</th><th class="text-end">Total (Rp)</th></tr></thead>
    <tbody>
        @forelse($revenue as $rev)
        <tr>
            <td>
                @if($groupBy === 'day') {{ \Carbon\Carbon::parse($rev->period)->translatedFormat('d M Y') }}
                @elseif($groupBy === 'year') {{ $rev->year }}
                @else {{ \Carbon\Carbon::createFromDate($rev->year, $rev->month, 1)->translatedFormat('F Y') }} @endif
            </td>
            <td class="text-end">{{ number_format($rev->total, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr><td colspan="2" class="text-center muted">Tidak ada data</td></tr>
        @endforelse
    </tbody>
</table>

<h3>Appointment per Status</h3>
<table class="data-table" style="margin-bottom:16px">
    <thead><tr><th>Status</th><th class="text-end">Jumlah</th></tr></thead>
    <tbody>
        @forelse($appointmentStatus as $item)
        <tr><td>{{ ucfirst($item->status) }}</td><td class="text-end">{{ $item->total }}</td></tr>
        @empty
        <tr><td colspan="2" class="text-center muted">Tidak ada data</td></tr>
        @endforelse
    </tbody>
</table>

<h3>Top 5 Tindakan</h3>
<table class="data-table">
    <thead><tr><th>#</th><th>Tindakan</th><th class="text-end">Jumlah</th></tr></thead>
    <tbody>
        @forelse($topTreatments as $i => $t)
        <tr><td>{{ $i + 1 }}</td><td>{{ $t->name }}</td><td class="text-end">{{ $t->total }}</td></tr>
        @empty
        <tr><td colspan="3" class="text-center muted">Tidak ada data</td></tr>
        @endforelse
    </tbody>
</table>

<div class="signature-block">
    <div class="signature-box">
        <div>Mengetahui,</div>
        <div class="signature-space"></div>
        <div>( ................................ )</div>
    </div>
    <div class="signature-box">
        <div>{{ now()->translatedFormat('d F Y') }}</div>
        <div>Bagian Keuangan</div>
        <div class="signature-space"></div>
        <div>( ................................ )</div>
    </div>
</div>
@endsection
