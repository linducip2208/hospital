@php $waNumber = cms('branding.meta.whatsapp_number', '6281296052010'); @endphp
<div class="sc-cta p-4 mb-4 text-center reveal" style="position:sticky;top:84px">
    <i class="bi bi-code-square fs-1"></i>
    <h5 class="fw-bold mt-2">Beli Source Code SIMRS</h5>
    <p class="small text-white-50 mb-3">Sistem rumah sakit lengkap — RME, BPJS, SatuSehat, farmasi, keuangan. Whitelabel &amp; self-host.</p>
    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo, saya tertarik dengan source code SIMRS') }}"
       class="btn btn-brand w-100 mb-2" target="_blank" rel="noopener">
        <i class="bi bi-whatsapp"></i> Chat WhatsApp
    </a>
    <a href="{{ url('/beli-aplikasi-rumah-sakit') }}" class="btn btn-outline-light w-100">Lihat Paket</a>
</div>

<div class="card-soft p-4">
    <h6 class="fw-bold mb-3">Modul Unggulan</h6>
    <ul class="list-unstyled small mb-0">
        @foreach(array_slice(array_values(\App\Support\SeoData::FEATURES), 0, 8) as $f)
            <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> {{ $f }}</li>
        @endforeach
    </ul>
</div>
