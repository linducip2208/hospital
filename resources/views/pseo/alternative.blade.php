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
            <div class="badge bg-white text-dark mb-2">Alternatif Terbaik</div>
            <h1 class="fw-bold display-6 mb-0">{{ $headline }}</h1>
        </div>
    </header>

    <div class="container-pub py-5">
        <div class="row g-5">
            <div class="col-lg-8">
                <article class="mb-4">
                    @foreach($intro as $p)<p>{{ $p }}</p>@endforeach
                </article>

                <h2 class="h4 fw-bold mb-3">{{ $appName }} vs {{ $competitor }}</h2>
                <div class="table-responsive card-soft p-2">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Aspek</th>
                                <th>{{ $competitor }}</th>
                                <th class="text-primary">{{ $appName }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comparison as $row)
                                <tr>
                                    <td class="fw-semibold">{{ $row['aspek'] }}</td>
                                    <td class="text-muted">{{ $row['them'] }}</td>
                                    <td><i class="bi bi-check-circle-fill text-success"></i> {{ $row['us'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @include('pseo._faq')
            </div>
            <div class="col-lg-4">
                @include('pseo._aside')
            </div>
        </div>
    </div>
</x-layouts.public>
