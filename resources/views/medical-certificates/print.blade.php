@extends('layouts.print')
@section('title', $certificate->type_label . ' - ' . $certificate->cert_no)
@section('content')
@php
    $patient = $certificate->patient;
    $doctor = $certificate->doctor;
    $age = $patient?->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->age : null;
    $gender = match($patient?->gender ?? '') { 'male' => 'Laki-laki', 'female' => 'Perempuan', default => $patient?->gender ?? '-' };
@endphp
<div class="doc-title">{{ strtoupper($certificate->type_label) }}</div>
<div class="doc-no">No: {{ $certificate->cert_no }}</div>

<p>Yang bertanda tangan di bawah ini, dokter pada {{ \App\Models\Setting::where('key','branding_app_name')->value('value') ?? config('app.name') }}, menerangkan bahwa:</p>

<table class="meta-table">
    <tr><td>Nama</td><td>: <strong>{{ $patient?->name }}</strong></td></tr>
    <tr><td>NIK</td><td>: {{ $patient?->nik ?? '-' }}</td></tr>
    <tr><td>Jenis Kelamin</td><td>: {{ $gender }}</td></tr>
    @if($age !== null)
    <tr><td>Umur</td><td>: {{ $age }} tahun</td></tr>
    @endif
    <tr><td>Alamat</td><td>: {{ $patient?->address ?? '-' }}</td></tr>
</table>

@switch($certificate->type)
    @case('sick_leave')
        <p style="margin-top:10px;">
            Setelah dilakukan pemeriksaan, pasien dinyatakan <strong>sakit</strong>
            @if($certificate->diagnosis) dengan diagnosis: <em>{{ $certificate->diagnosis }}</em>@endif
            dan memerlukan istirahat selama <strong>{{ $certificate->rest_days ?? '-' }} hari</strong>
            terhitung mulai tanggal <strong>{{ $certificate->rest_from?->translatedFormat('d F Y') }}</strong>
            sampai dengan <strong>{{ $certificate->rest_until?->translatedFormat('d F Y') }}</strong>.
        </p>
        @break
    @case('healthy')
        <p style="margin-top:10px;">
            Setelah dilakukan pemeriksaan kesehatan, yang bersangkutan dinyatakan <strong>SEHAT JASMANI</strong>
            dan tidak menderita penyakit menular berdasarkan pemeriksaan saat ini.
        </p>
        @if($certificate->exam_data)
        <table class="data-table" style="margin-top:10px;">
            <thead><tr><th>Pemeriksaan</th><th>Hasil</th></tr></thead>
            <tbody>
                @foreach((array) $certificate->exam_data as $k => $v)
                    <tr><td>{{ ucfirst(str_replace('_',' ', (string) $k)) }}</td><td>{{ is_array($v) ? json_encode($v) : $v }}</td></tr>
                @endforeach
            </tbody>
        </table>
        @endif
        @break
    @case('drug_free')
        <p style="margin-top:10px;">
            Berdasarkan pemeriksaan klinis dan/atau laboratorium, yang bersangkutan dinyatakan
            <strong>BEBAS dari penggunaan NARKOBA</strong>
            (Narkotika, Psikotropika dan Zat Adiktif lainnya).
        </p>
        @break
    @case('pregnancy')
        <p style="margin-top:10px;">
            Berdasarkan pemeriksaan, yang bersangkutan dinyatakan <strong>HAMIL</strong>
            @if($certificate->exam_data && isset($certificate->exam_data['gestational_age']))
                dengan usia kehamilan {{ $certificate->exam_data['gestational_age'] }}.
            @endif
        </p>
        @break
    @case('not_pregnancy')
        <p style="margin-top:10px;">
            Berdasarkan pemeriksaan, yang bersangkutan dinyatakan <strong>TIDAK HAMIL</strong>.
        </p>
        @break
    @case('birth')
        <p style="margin-top:10px;">
            Telah lahir seorang bayi dari pasien tersebut di atas dengan keterangan:
        </p>
        @if($certificate->exam_data)
        <table class="meta-table">
            @foreach(['baby_name'=>'Nama Bayi','birth_date_time'=>'Tgl/Jam Lahir','baby_gender'=>'Jenis Kelamin','weight'=>'Berat Badan','length'=>'Panjang Badan','birth_method'=>'Cara Persalinan'] as $k => $label)
                @if(isset($certificate->exam_data[$k]))
                    <tr><td>{{ $label }}</td><td>: {{ $certificate->exam_data[$k] }}</td></tr>
                @endif
            @endforeach
        </table>
        @endif
        @break
    @case('death')
        <p style="margin-top:10px;">
            Telah meninggal dunia atas nama tersebut di atas
            @if($certificate->exam_data && isset($certificate->exam_data['death_date_time']))
                pada {{ $certificate->exam_data['death_date_time'] }}
            @endif
            @if($certificate->diagnosis) karena <em>{{ $certificate->diagnosis }}</em>@endif.
        </p>
        @break
    @case('visum')
        <p style="margin-top:10px;">
            Atas permintaan {{ $certificate->purpose ?? 'penyidik' }}, telah dilakukan pemeriksaan visum
            terhadap yang bersangkutan dengan hasil:
        </p>
        <p>{{ $certificate->diagnosis }}</p>
        @if($certificate->notes)<p>{{ $certificate->notes }}</p>@endif
        @break
    @case('color_blind_free')
        <p style="margin-top:10px;">
            Berdasarkan pemeriksaan, yang bersangkutan dinyatakan <strong>BEBAS BUTA WARNA</strong>.
        </p>
        @break
    @case('medical_check_up')
        <p style="margin-top:10px;">Hasil pemeriksaan kesehatan menyeluruh:</p>
        @if($certificate->exam_data)
        <table class="data-table">
            <thead><tr><th>Pemeriksaan</th><th>Hasil</th></tr></thead>
            <tbody>
                @foreach((array) $certificate->exam_data as $k => $v)
                    <tr><td>{{ ucfirst(str_replace('_',' ', (string) $k)) }}</td><td>{{ is_array($v) ? json_encode($v) : $v }}</td></tr>
                @endforeach
            </tbody>
        </table>
        @endif
        @break
@endswitch

@if($certificate->purpose && !in_array($certificate->type, ['visum']))
    <p>Surat ini dibuat untuk keperluan: <strong>{{ $certificate->purpose }}</strong>.</p>
@endif

@if($certificate->notes && !in_array($certificate->type, ['visum']))
    <p class="small muted">Catatan: {{ $certificate->notes }}</p>
@endif

<div class="signature-block">
    <div></div>
    <div class="signature-box">
        <div>{{ \App\Models\Setting::where('key','branding_city')->value('value') ?? '....................' }},
            {{ ($certificate->issue_date ?? now())->translatedFormat('d F Y') }}</div>
        <div>Dokter Pemeriksa,</div>
        <div class="signature-space"></div>
        <div><strong>{{ $doctor?->name ?? '(.....................)' }}</strong></div>
        @if($doctor?->str_number)<div class="small">STR: {{ $doctor->str_number }}</div>@endif
    </div>
</div>
@endsection
