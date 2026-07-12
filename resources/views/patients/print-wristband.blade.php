<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Gelang - {{ $patient->name }}</title>
<style>
@page { size: 250mm 25mm; margin: 0; }
body { font-family: Arial, sans-serif; font-size:9pt; margin:0; padding:0; }
.band {
    width: 250mm; height: 25mm; padding:3mm 6mm; box-sizing:border-box;
    background: {{ ($patient->gender ?? '') === 'female' ? '#fbcfe8' : '#bfdbfe' }};
    border:1px dashed #444;
}
.band .row { display:flex; justify-content:space-between; align-items:center; }
.band .name { font-size:13pt; font-weight:bold; }
.band .small { font-size:8pt; }
.alerts { display:flex; gap:5px; margin-top:1mm; }
.alert-tag { padding:1px 6px; border-radius:3px; font-size:7pt; font-weight:bold; color:#fff; }
.alert-red { background:#dc2626; }
.alert-yellow { background:#ca8a04; color:#000; }
.no-print { display:block; padding:8px; background:#f3f4f6; text-align:center; }
.no-print button, .no-print a { padding:6px 14px; margin:0 4px; background:#2563eb; color:#fff; border:none; border-radius:4px; text-decoration:none; cursor:pointer; }
@media print { .no-print { display:none !important; } body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
</style>
</head>
<body>
<div class="no-print">
    <button onclick="window.print()">🖨️ Cetak Gelang</button>
    <a href="javascript:history.back()">← Kembali</a>
    <div style="font-size:11px; margin-top:4px; color:#555;">Warna gelang: {{ ($patient->gender ?? '') === 'female' ? 'Pink (perempuan)' : 'Biru (laki-laki)' }}. Alergi → tambah stiker merah, risiko jatuh → kuning.</div>
</div>
<div class="band">
    <div class="row">
        <div>
            <div class="name">{{ strtoupper($patient->name) }}</div>
            <div class="small">No. RM: <strong>{{ str_pad((string) $patient->id, 8, '0', STR_PAD_LEFT) }}</strong> &middot; {{ $patient->birth_date?->format('d/m/Y') }} &middot; {{ $patient->gender }}</div>
            @if($patient->allergies || $patient->blood_type)
            <div class="alerts">
                @if($patient->allergies)<span class="alert-tag alert-red">ALERGI: {{ Str::limit($patient->allergies, 30) }}</span>@endif
                @if($patient->blood_type)<span class="alert-tag alert-yellow">GOL DARAH: {{ $patient->blood_type }}</span>@endif
            </div>
            @endif
        </div>
        <div style="text-align:right;">
            <div style="font-size:14pt; font-family:'Courier New';">||||| ||| | ||||| || | |||| ||</div>
            <div class="small">{{ str_pad((string) $patient->id, 8, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>
</div>
</body>
</html>
