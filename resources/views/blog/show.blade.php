@php
    $seoTitle = $post->meta_title ?: ($post->title . ' — ' . cms('branding.title', config('app.name')));
    $seoDescription = $post->meta_description ?: Str::limit(strip_tags($post->excerpt ?: $post->content), 155);
    $seoCanonical = route('blog.show', $post);
    $ogType = 'article';
    $ogImage = $post->featured_image ?: null;
    $jsonLd = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $post->title,
        'description' => $seoDescription,
        'image' => $post->featured_image ? [$post->featured_image] : [],
        'datePublished' => $post->published_at?->toIso8601String(),
        'dateModified' => $post->updated_at?->toIso8601String(),
        'author' => ['@type' => 'Person', 'name' => $post->author?->name ?? cms('branding.title', config('app.name'))],
        'publisher' => ['@type' => 'Organization', 'name' => cms('branding.title', config('app.name'))],
        'mainEntityOfPage' => $seoCanonical,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

<x-layouts.public
    :seoTitle="$seoTitle"
    :seoDescription="$seoDescription"
    :seoCanonical="$seoCanonical"
    :ogType="$ogType"
    :ogImage="$ogImage"
    :jsonLd="$jsonLd">

    <div class="container-pub py-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="small mb-3">
                    <a href="{{ route('blog.index') }}">Blog</a>
                    @if($post->category) / <a href="{{ route('blog.category', $post->category) }}">{{ $post->category->name }}</a>@endif
                </nav>

                <article class="card-soft p-4 p-md-5">
                    @if($post->category)
                        <a href="{{ route('blog.category', $post->category) }}" class="badge text-bg-light mb-2">{{ $post->category->name }}</a>
                    @endif
                    <h1 class="fw-bold mb-3">{{ $post->title }}</h1>
                    <div class="text-muted small mb-4">
                        <i class="bi bi-person-circle"></i> {{ $post->author?->name ?? 'Redaksi' }}
                        · <i class="bi bi-calendar3"></i> {{ $post->published_at?->translatedFormat('d F Y') }}
                        · <i class="bi bi-eye"></i> {{ number_format($post->views) }}x dibaca
                    </div>
                    @if($post->featured_image)
                        <img src="{{ $post->featured_image }}" class="w-100 rounded mb-4" style="max-height:420px;object-fit:cover" alt="{{ $post->title }}">
                    @endif
                    <div class="blog-content">{!! $post->content !!}</div>
                </article>

                @if($related->count())
                    <h5 class="fw-bold mt-5 mb-3">Artikel Terkait</h5>
                    <div class="row g-3">
                        @foreach($related as $r)
                            <div class="col-md-4">
                                <a href="{{ route('blog.show', $r) }}" class="card-soft d-block h-100 p-3 text-dark">
                                    <span class="fw-semibold">{{ Str::limit($r->title, 60) }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                @include('blog._sidebar')
            </div>
        </div>
    </div>
</x-layouts.public>
