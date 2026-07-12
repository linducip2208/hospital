@php
    $waNumber = cms('branding.meta.whatsapp_number', '6281296052010');
    $waText = urlencode('Halo, saya tertarik dengan ' . $keyword . ($cityName ? ' di ' . $cityName : ''));
    $faqLd = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($faqs)->map(fn ($f) => [
            '@type' => 'Question',
            'name' => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
        ])->values()->all(),
    ];
    $productLd = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $headline,
        'description' => $seoDescription,
        'brand' => ['@type' => 'Brand', 'name' => $appName],
    ];
    $jsonLd = json_encode([$productLd, $faqLd], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

<x-layouts.public :seoTitle="$seoTitle" :seoDescription="$seoDescription" :seoCanonical="$canonical" :jsonLd="$jsonLd">
    <header class="hero-grad py-5">
        <div class="container-pub py-4">
            <div class="badge bg-white text-dark mb-2">Source Code SIMRS</div>
            <h1 class="fw-bold display-6 mb-3">{{ $headline }}</h1>
            <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" class="btn btn-light btn-lg fw-semibold" target="_blank" rel="noopener">
                <i class="bi bi-whatsapp"></i> Konsultasi &amp; Beli Sekarang
            </a>
        </div>
    </header>

    <div class="container-pub py-5">
        <div class="row g-5">
            <div class="col-lg-8">
                <article class="mb-4">
                    @foreach($intro as $p)<p>{{ $p }}</p>@endforeach
                </article>

                <h2 class="h4 fw-bold mb-3">Yang Anda Dapatkan</h2>
                <div class="row g-3 mb-4">
                    @foreach($features as $slug => $name)
                        <div class="col-md-6">
                            <div class="card-soft p-3 h-100 reveal">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span class="fw-semibold ms-1">{{ $name }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="card-soft p-4 mb-4">
                    <h3 class="h5 fw-bold mb-3">Kenapa Memilih Source Code Kami?</h3>
                    <ul class="mb-0">
                        <li>Full source code — bebas modifikasi tanpa batasan vendor.</li>
                        <li>Whitelabel — ganti logo, nama, dan warna sesuai brand Anda.</li>
                        <li>Self-host — pasang di server sendiri, data 100% milik Anda.</li>
                        <li>Integrasi BPJS (VClaim &amp; Antrean) dan SatuSehat Kemenkes.</li>
                        <li>Sekali beli, tanpa biaya langganan berkelanjutan.</li>
                        <li>Dokumentasi lengkap &amp; dukungan implementasi.</li>
                    </ul>
                </div>

                @include('pseo._faq')
            </div>
            <div class="col-lg-4">
                @include('pseo._aside')
            </div>
        </div>
    </div>
</x-layouts.public>
