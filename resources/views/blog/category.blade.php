@php
    $seoTitle = 'Kategori: ' . $category->name . ' — ' . cms('branding.title', config('app.name'));
    $seoDescription = $category->description ?: ('Kumpulan artikel dalam kategori ' . $category->name . '.');
    $seoCanonical = route('blog.category', $category);
@endphp

<x-layouts.public
    :seoTitle="$seoTitle"
    :seoDescription="$seoDescription"
    :seoCanonical="$seoCanonical">

    <header class="hero-grad py-5">
        <div class="container-pub py-4">
            <div class="small mb-2"><a href="{{ route('blog.index') }}" class="text-white-50">Blog</a> / {{ $category->name }}</div>
            <h1 class="fw-bold display-6 mb-2">{{ $category->name }}</h1>
            @if($category->description)<p class="mb-0 text-white-50">{{ $category->description }}</p>@endif
        </div>
    </header>

    <div class="container-pub py-5">
        <div class="row g-4">
            <div class="col-lg-8">
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
                                        <h5 class="fw-bold"><a href="{{ route('blog.show', $post) }}" class="text-dark">{{ $post->title }}</a></h5>
                                        <p class="text-muted small">{{ Str::limit($post->excerpt ?: strip_tags($post->content), 110) }}</p>
                                        <div class="small text-muted"><i class="bi bi-calendar3"></i> {{ $post->published_at?->translatedFormat('d M Y') }}</div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">{{ $posts->links() }}</div>
                @else
                    <div class="card-soft p-5 text-center text-muted">Belum ada artikel di kategori ini.</div>
                @endif
            </div>
            <div class="col-lg-4">
                @include('blog._sidebar')
            </div>
        </div>
    </div>
</x-layouts.public>
