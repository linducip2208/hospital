<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Stiker Pasien - {{ $patient->name }}</title>
<style>
@page { size: A4; margin: 8mm; }
body { font-family: Arial, sans-serif; font-size:8pt; margin:0; }
.grid { display:grid; grid-template-columns: repeat(4, 1fr); gap:2mm; }
.stk {
    border:1px dashed #999; padding:2mm 3mm; min-height:25mm;
    border-radius:3px; background:#fff;
    page-break-inside:avoid;
}
.stk .name { font-size:9pt; font-weight:bold; }
.stk .small { font-size:7pt; color:#444; }
.no-print { padding:8px; text-align:center; background:#f3f4f6; border-bottom:1px solid #ccc; }
.no-print button, .no-print a { padding:6px 14px; margin:0 4px; background:#2563eb; color:#fff; border:none; border-radius:4px; cursor:pointer; text-decoration:none; }
@media print { .no-print { display:none !important; } }
</style>
</head>
<body>
<div class="no-print">
    <button onclick="window.print()">🖨️ Cetak Stiker</button>
    <a href="javascript:history.back()">← Kembali</a>
</div>
<div class="grid">
    @for($i = 0; $i < 32; $i++)
    <div class="stk">
        <div class="name">{{ strtoupper($patient->name) }}</div>
        <div class="small">No. RM: <strong>{{ str_pad((string) $patient->id, 8, '0', STR_PAD_LEFT) }}</strong></div>
        <div class="small">{{ $patient->birth_date?->format('d/m/Y') }} &middot; {{ $patient->gender }}</div>
        <div class="small">NIK: {{ $patient->nik ?? '-' }}</div>
        @if($patient->allergies)<div class="small" style="color:#dc2626; font-weight:bold;">⚠ {{ Str::limit($patient->allergies, 25) }}</div>@endif
    </div>
    @endfor
</div>
</body>
</html>
