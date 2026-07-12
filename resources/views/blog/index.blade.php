@php
    $seoTitle = 'Blog Kesehatan & Manajemen Rumah Sakit — ' . cms('branding.title', config('app.name'));
    $seoDescription = 'Artikel seputar manajemen rumah sakit, rekam medis elektronik, BPJS, SatuSehat, dan digitalisasi layanan kesehatan.';
    $seoCanonical = route('blog.index');
    $jsonLd = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Blog',
        'name' => $seoTitle,
        'url' => $seoCanonical,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

<x-layouts.public
    :seoTitle="$seoTitle"
    :seoDescription="$seoDescription"
    :seoCanonical="$seoCanonical"
    :jsonLd="$jsonLd">

    <header class="hero-grad py-5">
        <div class="container-pub py-4">
            <h1 class="fw-bold display-6 mb-2">Blog</h1>
            <p class="mb-0 text-white-50" style="max-width:620px">Wawasan seputar manajemen rumah sakit, rekam medis elektronik, integrasi BPJS &amp; SatuSehat, dan transformasi digital layanan kesehatan.</p>
        </div>
    </header>

    <div class="container-pub py-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <form method="get" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="q" value="{{ $search ?? '' }}" class="form-control" placeholder="Cari artikel...">
                        <button class="btn btn-brand" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                @if($posts->count())
                    <div class="row g-4">
                        @foreach($posts as $post)
                            <div class="col-md-6">
                                <article class="card-soft h-100 overflow-hidden reveal">
                                    <a href="{{ route('blog.show', $post) }}">
                                        <img src="{{ $post->featured_image ?: 'https://images.unsplash.com/photo-1538108149393-fbbd81895907?auto=format&fit=crop&w=800&q=70' }}"
                                             class="w-100" style="height:180px;object-fit:cover" alt="{{ $post->title }}">
                                    </a>
                                    <div class="p-3">
                                        @if($post->category)
                                            <a href="{{ route('blog.category', $post->category) }}" class="badge text-bg-light mb-2">{{ $post->category->name }}</a>
                                        @endif
                                        <h5 class="fw-bold"><a href="{{ route('blog.show', $post) }}" class="text-dark">{{ $post->title }}</a></h5>
                                        <p class="text-muted small">{{ Str::limit($post->excerpt ?: strip_tags($post->content), 110) }}</p>
                                        <div class="d-flex justify-content-between align-items-center small text-muted">
                                            <span><i class="bi bi-calendar3"></i> {{ $post->published_at?->translatedFormat('d M Y') }}</span>
                                            <a href="{{ route('blog.show', $post) }}">Baca →</a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">{{ $posts->links() }}</div>
                @else
                    <div class="card-soft p-5 text-center text-muted">Belum ada artikel.</div>
                @endif
            </div>

            <div class="col-lg-4">
                @include('blog._sidebar')
            </div>
        </div>
    </div>
</x-layouts.public>
