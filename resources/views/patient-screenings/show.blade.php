@extends('layouts.admin')
@section('title','Detail Skrining')
@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Skrining <code>{{ $screening->screening_no }}</code></h1>
    <div>
        <a href="{{ route('patient-screenings.print', $screening) }}" target="_blank" class="btn btn-success"><i class="bi bi-printer"></i> Cetak</a>
        <a href="{{ route('patient-screenings.edit', $screening) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('patient-screenings.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
<div class="card shadow-sm"><div class="card-body">
<table class="table">
<tr><th>Jenis</th><td>{{ $screening->type_label }}</td><th>Pasien</th><td>{{ $screening->patient->name ?? '-' }}</td></tr>
<tr><th>Tanggal</th><td>{{ $screening->screened_at?->format('d M Y H:i') }}</td><th>Petugas</th><td>{{ $screening->user->name ?? '-' }}</td></tr>
<tr><th>Skor</th><td>{{ $screening->score }}</td><th>Risiko</th><td><span class="badge bg-{{ ['low'=>'success','moderate'=>'warning','high'=>'danger'][$screening->risk_level] }}">{{ $screening->risk_level }}</span></td></tr>
<tr><th>Intervensi</th><td colspan="3">{!! nl2br(e($screening->intervention)) !!}</td></tr>
<tr><th>Catatan</th><td colspan="3">{!! nl2br(e($screening->notes)) !!}</td></tr>
</table>
@if($screening->answers)<h6>Jawaban</h6><pre class="bg-light p-2 small">{{ json_encode($screening->answers, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>@endif
</div></div>
@endsection
