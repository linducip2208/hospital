@php
    $jsonLdData = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $headline,
        'itemListElement' => collect($items)->map(fn ($it) => [
            '@type' => 'ListItem',
            'position' => $it['rank'],
            'name' => $it['title'],
        ])->values()->all(),
    ];
    $faqLd = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($faqs)->map(fn ($f) => [
            '@type' => 'Question',
            'name' => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
        ])->values()->all(),
    ];
    $jsonLd = json_encode([$jsonLdData, $faqLd], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

<x-layouts.public :seoTitle="$seoTitle" :seoDescription="$seoDescription" :seoCanonical="$canonical" :jsonLd="$jsonLd">
    <header class="hero-grad py-5">
        <div class="container-pub py-4">
            <div class="badge bg-white text-dark mb-2">{{ $kicker ?? 'Rekomendasi' }}</div>
            <h1 class="fw-bold display-6 mb-0">{{ $headline }}</h1>
        </div>
    </header>

    <div class="container-pub py-5">
        <div class="row g-5">
            <div class="col-lg-8">
                <article class="mb-4">
                    @foreach($intro as $p)<p>{{ $p }}</p>@endforeach
                </article>

                <h2 class="h4 fw-bold mb-3">Daftar Rekomendasi</h2>
                <div class="vstack gap-3">
                    @foreach($items as $it)
                        <div class="card-soft p-3 d-flex flex-row align-items-start gap-3 reveal">
                            <div class="badge hero-grad fs-6 rounded-circle p-3">{{ $it['rank'] }}</div>
                            <div>
                                <h3 class="h6 fw-bold mb-1">{{ $it['title'] }}</h3>
                                <p class="text-muted mb-0 small">{{ $it['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                @include('pseo._faq')
            </div>
            <div class="col-lg-4">
                @include('pseo._aside')
            </div>
        </div>
    </div>
</x-layouts.public>
