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
            <div class="badge bg-white text-dark mb-2">Perbandingan</div>
            <h1 class="fw-bold display-6 mb-0">{{ $headline }}</h1>
        </div>
    </header>

    <div class="container-pub py-5">
        <div class="row g-5">
            <div class="col-lg-8">
                <article class="mb-4">
                    @foreach($intro as $p)<p>{{ $p }}</p>@endforeach
                </article>

                <h2 class="h4 fw-bold mb-3">Perbandingan Head-to-Head</h2>
                <div class="table-responsive card-soft p-2">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr><th>Aspek</th><th>{{ $a }}</th><th>{{ $b }}</th></tr>
                        </thead>
                        <tbody>
                            @foreach($rows as $row)
                                <tr>
                                    <td class="fw-semibold">{{ $row['aspek'] }}</td>
                                    <td class="text-muted">{{ $row[$a] ?? '-' }}</td>
                                    <td class="text-muted">{{ $row[$b] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-primary mt-4">
                    <strong>Kesimpulan:</strong> Baik {{ $a }} maupun {{ $b }} memiliki kelebihan masing-masing.
                    Jika Anda menginginkan solusi dengan source code lengkap, whitelabel, dan integrasi BPJS &amp; SatuSehat siap pakai,
                    pertimbangkan {{ cms('branding.title', config('app.name')) }}.
                </div>

                @include('pseo._faq')
            </div>
            <div class="col-lg-4">
                @include('pseo._aside')
            </div>
        </div>
    </div>
</x-layouts.public>
