<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kartu Berobat - {{ $patient->name }}</title>
<style>
@page { size: 86mm 54mm; margin: 0; }
body { font-family: Arial, sans-serif; font-size:10pt; margin:0; padding:0; }
.card { width: 86mm; height: 54mm; padding:6mm; border:1px solid #888; border-radius:8px; background:linear-gradient(135deg,#0ea5e9,#0c4a6e); color:#fff; box-sizing:border-box; }
.brand { font-size:9pt; opacity:0.9; }
.title { font-size:13pt; font-weight:bold; margin:2px 0; }
.no { font-size:11pt; letter-spacing:1px; }
.row { display:flex; justify-content:space-between; align-items:flex-end; margin-top:6mm; }
.no-print { display:block; padding:8px; background:#f3f4f6; text-align:center; }
.no-print button, .no-print a { padding:6px 14px; margin:0 4px; background:#2563eb; color:#fff; border:none; border-radius:4px; cursor:pointer; text-decoration:none; }
@media print { .no-print { display:none !important; } body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
</style>
</head>
<body>
@php $brand = \App\Models\Setting::where('group','branding')->pluck('value','key'); @endphp
<div class="no-print">
    <button onclick="window.print()">🖨️ Cetak Kartu</button>
    <a href="javascript:history.back()">← Kembali</a>
</div>
<div class="card">
    <div class="brand">{{ $brand['branding_app_name'] ?? config('app.name') }}</div>
    <div class="title">KARTU BEROBAT</div>
    <div style="margin-top:3mm;">
        <div style="font-size:8pt; opacity:0.7;">No. Rekam Medis</div>
        <div class="no">{{ str_pad((string) $patient->id, 8, '0', STR_PAD_LEFT) }}</div>
    </div>
    <div class="row">
        <div>
            <div style="font-size:8pt; opacity:0.7;">Nama</div>
            <div style="font-size:11pt; font-weight:bold;">{{ Str::limit($patient->name, 30) }}</div>
            <div style="font-size:8pt;">{{ $patient->birth_date?->format('d/m/Y') }} &middot; {{ strtoupper(substr((string) $patient->gender, 0, 1)) }}</div>
        </div>
    </div>
</div>
</body>
</html>
