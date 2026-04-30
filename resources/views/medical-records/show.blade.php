@extends('layouts.admin')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Detail Rekam Medis</h1>
    <div>
        <a href="{{ route('medical-records.edit', $medicalRecord) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th style="width:150px">Pasien</th><td><a href="{{ route('patients.show', $medicalRecord->patient) }}">{{ $medicalRecord->patient->name ?? '-' }}</a></td></tr>
            <tr><th>Dokter</th><td><a href="{{ route('doctors.show', $medicalRecord->doctor) }}">{{ $medicalRecord->doctor->name ?? '-' }}</a></td></tr>
            @if($medicalRecord->appointment)
            <tr><th>Appointment</th><td>{{ $medicalRecord->appointment->appointment_date->format('d/m/Y H:i') }}</td></tr>
            @endif
            <tr><th>Tanggal</th><td>{{ $medicalRecord->created_at->format('d/m/Y H:i') }}</td></tr>
            <tr><th>Diagnosis</th><td>{{ $medicalRecord->diagnosis }}</td></tr>
            @if($medicalRecord->action)
            <tr><th>Tindakan</th><td>{{ $medicalRecord->action }}</td></tr>
            @endif
            @if($medicalRecord->medicine)
            <tr><th>Obat / Resep</th><td>{!! nl2br(e($medicalRecord->medicine)) !!}</td></tr>
            @endif
            @if($medicalRecord->lab_results)
            <tr><th>Hasil Lab</th><td>{!! nl2br(e($medicalRecord->lab_results)) !!}</td></tr>
            @endif
            @if($medicalRecord->notes)
            <tr><th>Catatan</th><td>{{ $medicalRecord->notes }}</td></tr>
            @endif
        </table>
    </div>
</div>
@endsection
