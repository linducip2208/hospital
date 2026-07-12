@extends('layouts.admin')

@section('title', 'Detail Rujukan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Rujukan</h1>
    <div>
        <a href="{{ route('referrals.edit', $referral) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('referrals.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 text-secondary mb-2"><i class="bi bi-arrow-left-right"></i></div>
                <h5>{{ $referral->patient->name ?? '-' }}</h5>
                @php
                    $statusColors = [
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'info',
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$referral->status] ?? 'secondary' }}">
                    {{ ucfirst($referral->status) }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Dari Poli:</strong> {{ $referral->fromPolyclinic->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Ke Poli:</strong> {{ $referral->toPolyclinic->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Dokter:</strong> {{ $referral->doctor->name ?? '-' }}</li>
                <li class="list-group-item"><strong>Tanggal:</strong> {{ $referral->created_at->format('d/m/Y H:i') }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">Alasan Rujukan</div>
            <div class="card-body">
                <p class="mb-0">{{ $referral->reason }}</p>
            </div>
        </div>

        @if($referral->diagnosis)
        <div class="card shadow-sm mb-3">
            <div class="card-header">Diagnosis</div>
            <div class="card-body">
                <p class="mb-0">{{ $referral->diagnosis }}</p>
            </div>
        </div>
        @endif

        @if($referral->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $referral->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
