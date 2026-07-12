{{-- Blog sidebar: categories, recent posts, source code CTA --}}
<div class="card-soft p-4 mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-folder2"></i> Kategori</h6>
    @if($categories->count())
        <ul class="list-unstyled mb-0">
            @foreach($categories as $cat)
                <li class="mb-2 d-flex justify-content-between">
                    <a href="{{ route('blog.category', $cat) }}">{{ $cat->name }}</a>
                    <span class="badge text-bg-light">{{ $cat->posts_count }}</span>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted small mb-0">Belum ada kategori.</p>
    @endif
</div>

<div class="card-soft p-4 mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-clock-history"></i> Artikel Terbaru</h6>
    @forelse($recent as $r)
        <a href="{{ route('blog.show', $r) }}" class="d-flex gap-2 mb-3 text-dark">
            <img src="{{ $r->featured_image ?: 'https://images.unsplash.com/photo-1538108149393-fbbd81895907?auto=format&fit=crop&w=200&q=60' }}"
                 style="width:56px;height:56px;object-fit:cover;border-radius:8px" alt="">
            <span class="small fw-semibold">{{ Str::limit($r->title, 60) }}</span>
        </a>
    @empty
        <p class="text-muted small mb-0">Belum ada artikel.</p>
    @endforelse
</div>

<div class="sc-cta p-4 text-center">
    <i class="bi bi-code-square fs-1"></i>
    <h6 class="fw-bold mt-2">Beli Source Code SIMRS</h6>
    <p class="small text-white-50">Sistem rumah sakit lengkap, siap whitelabel.</p>
    <a href="{{ url('/beli-aplikasi-rumah-sakit') }}" class="btn btn-brand btn-sm w-100">Lihat Detail</a>
</div>
