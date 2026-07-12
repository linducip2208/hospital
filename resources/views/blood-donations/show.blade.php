@extends('layouts.admin')

@section('title', 'Detail Donor Darah')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Donor Darah</h1>
    <div>
        <a href="{{ route('blood-donations.edit', $bloodDonation) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('blood-donations.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <div class="display-1 mb-2 text-danger">
                    <i class="bi bi-droplet-fill"></i>
                </div>
                <h5>
                    <span class="badge bg-danger fs-5">{{ $bloodDonation->blood_type }}</span>
                </h5>
                @php
                    $statusColors = [
                        'available' => 'success',
                        'used' => 'secondary',
                        'expired' => 'danger',
                        'discarded' => 'dark',
                    ];
                @endphp
                <span class="badge bg-{{ $statusColors[$bloodDonation->status] ?? 'secondary' }}">
                    {{ ucfirst($bloodDonation->status) }}
                </span>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Donor:</strong> {{ $bloodDonation->donor_name }}</li>
                <li class="list-group-item"><strong>Tgl Donor:</strong> {{ $bloodDonation->donation_date ? $bloodDonation->donation_date->format('d/m/Y H:i') : '-' }}</li>
                <li class="list-group-item"><strong>Tgl Kadaluarsa:</strong> {{ $bloodDonation->expiry_date ? $bloodDonation->expiry_date->format('d/m/Y') : '-' }}</li>
                <li class="list-group-item"><strong>Volume:</strong> {{ $bloodDonation->quantity_ml ? $bloodDonation->quantity_ml . ' ml' : '-' }}</li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        @if($bloodDonation->notes)
        <div class="card shadow-sm">
            <div class="card-header">Catatan</div>
            <div class="card-body">
                <p class="mb-0">{{ $bloodDonation->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
