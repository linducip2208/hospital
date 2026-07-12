@php
    $faqLd = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($faqs)->map(fn ($f) => [
            '@type' => 'Question',
            'name' => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
        ])->values()->all(),
    ];
    $jsonLd = json_encode($faqLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

<x-layouts.public :seoTitle="$seoTitle" :seoDescription="$seoDescription" :seoCanonical="$canonical" :jsonLd="$jsonLd">
    <header class="hero-grad py-5">
        <div class="container-pub py-4">
            <div class="badge bg-white text-dark mb-2">Fitur</div>
            <h1 class="fw-bold display-6 mb-0">{{ $headline }}</h1>
        </div>
    </header>

    <div class="container-pub py-5">
        <div class="row g-5">
            <div class="col-lg-8">
                <article class="mb-4">
                    @foreach($intro as $p)<p>{{ $p }}</p>@endforeach
                </article>

                <h2 class="h4 fw-bold mb-3">Modul Terkait</h2>
                <div class="row g-3">
                    @foreach($relatedFeatures as $slug => $name)
                        <div class="col-md-4">
                            <a href="{{ url('/fitur-' . $slug) }}" class="card-soft d-block h-100 p-3 text-dark reveal">
                                <i class="bi bi-grid-1x2-fill text-primary"></i>
                                <div class="fw-semibold mt-2">{{ $name }}</div>
                            </a>
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
