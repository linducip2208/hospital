<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Etiket - {{ $prescription->rx_no }}</title>
<style>
@page { size: A4; margin: 8mm; }
body { font-family: Arial, sans-serif; font-size:9pt; margin:0; }
.label-grid { display:grid; grid-template-columns: 1fr 1fr; gap:4mm; }
.label {
    border:1px solid #444; padding:6px 8px; min-height:55mm; page-break-inside:avoid;
    border-radius:4px;
}
.label.oral { background:#fff; }
.label.topical { background:#e0f2fe; }
.label-header { display:flex; justify-content:space-between; border-bottom:1px solid #000; padding-bottom:3px; margin-bottom:5px; }
.label-header strong { font-size:11pt; }
.tag { font-size:8pt; padding:1px 4px; border:1px solid #000; border-radius:2px; }
.tag.oral { background:#fff; }
.tag.topical { background:#0284c7; color:#fff; }
.tag.ha { background:red; color:#fff; }
.usage { font-size:14pt; font-weight:bold; margin:6px 0; }
.no-print { display:block; }
.print-actions { padding:8px; background:#f3f4f6; text-align:center; border-bottom:1px solid #ccc; }
.print-actions button, .print-actions a { padding:6px 14px; margin:0 4px; background:#2563eb; color:#fff; border:none; border-radius:4px; cursor:pointer; text-decoration:none; }
.print-actions a.secondary { background:#6b7280; }
@media print { .no-print { display:none !important; } body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
</style>
</head>
<body>
@php
    $brand = \App\Models\Setting::where('group','branding')->pluck('value','key');
    $hospital = $brand['branding_app_name'] ?? config('app.name');
@endphp
<div class="no-print print-actions">
    <button onclick="window.print()">🖨️ Cetak Etiket</button>
    <a href="javascript:history.back()" class="secondary">← Kembali</a>
</div>
<div class="label-grid">
    @foreach($prescription->items as $it)
    @php $isTopical = in_array(strtolower((string) $it->route), ['topikal','topical','luar','salep','krim']); @endphp
    <div class="label {{ $isTopical ? 'topical' : 'oral' }}">
        <div class="label-header">
            <strong>{{ $hospital }}</strong>
            <span class="tag {{ $isTopical ? 'topical' : 'oral' }}">{{ $isTopical ? 'OBAT LUAR' : 'OBAT DALAM' }}</span>
        </div>
        <div>No. R/: <strong>{{ $prescription->rx_no }}</strong> &nbsp; Tgl: {{ $prescription->prescribed_at?->format('d/m/Y') }}</div>
        <div>Pasien: <strong>{{ $prescription->patient->name ?? '-' }}</strong></div>
        <div style="margin-top:5px;">
            <strong style="font-size:11pt;">{{ $it->drug_name }}</strong>
            @if($it->dose) — {{ $it->dose }}@endif
            @if($it->is_high_alert) <span class="tag ha">HIGH ALERT</span>@endif
        </div>
        <div class="usage">
            {{ $it->frequency ?? '-' }}
            @if($it->frequency && $it->unit) {{ $it->unit }}@endif
        </div>
        <div>{{ $it->instructions }}</div>
        <div style="margin-top:6px; border-top:1px dashed #888; padding-top:3px;">
            Dokter: {{ $prescription->doctor->name ?? '-' }}<br>
            <span class="small">Simpan di tempat sejuk, jauh dari jangkauan anak.</span>
        </div>
    </div>
    @endforeach
</div>
</body>
</html>
