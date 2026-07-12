<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>@yield('title', 'Cetak')</title>
<style>
    @page { size: @yield('page_size', 'A4'); margin: @yield('page_margin', '15mm'); }
    * { box-sizing: border-box; }
    body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #000; margin: 0; padding: 0; }
    h1, h2, h3 { margin: 6px 0; }
    table { border-collapse: collapse; width: 100%; }
    .doc-title { text-align:center; font-size:14pt; font-weight:bold; text-decoration:underline; margin:10px 0; }
    .doc-no { text-align:center; margin-bottom:14px; }
    .signature-block { margin-top:40px; display:flex; justify-content:space-between; }
    .signature-box { text-align:center; min-width:180px; }
    .signature-space { height:70px; }
    .data-table th, .data-table td { padding:6px 8px; border:1px solid #444; vertical-align:top; }
    .meta-table td { padding:3px 6px; vertical-align:top; }
    .meta-table td:first-child { width:160px; }
    .text-end { text-align:right; }
    .text-center { text-align:center; }
    .small { font-size: 10pt; }
    .muted { color:#555; }
    .no-print { display: block; }
    .print-actions { padding:12px; background:#f3f4f6; border-bottom:1px solid #ccc; text-align:center; }
    .print-actions a, .print-actions button {
        display:inline-block; padding:6px 14px; margin:0 4px; background:#2563eb; color:#fff;
        border:none; border-radius:4px; text-decoration:none; cursor:pointer; font-size:13px;
    }
    .print-actions a.secondary { background:#6b7280; }
    @media print {
        .no-print { display: none !important; }
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
</head>
<body>
<div class="no-print print-actions">
    <button onclick="window.print()">🖨️ Cetak</button>
    <a href="javascript:history.back()" class="secondary">← Kembali</a>
</div>
<div class="doc-content">
    @include('partials.letterhead')
    @yield('content')
</div>
<script>
    @if(request()->boolean('autoprint'))
    window.addEventListener('load', () => setTimeout(() => window.print(), 400));
    @endif
</script>
</body>
</html>
