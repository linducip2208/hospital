@extends('layouts.admin')

@section('title', 'Blog — Artikel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header">
    <h1 class="h2">Blog — Artikel</h1>
    <div>
        <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-outline-secondary"><i class="bi bi-folder2"></i> Kategori</a>
        <a href="{{ route('admin.blog.posts.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tulis Artikel</a>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col">
                <input type="text" name="search" class="form-control" placeholder="Cari judul artikel..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Views</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr>
                        <td>{{ $loop->iteration + ($posts->currentPage() - 1) * $posts->perPage() }}</td>
                        <td>{{ Str::limit($post->title, 50) }}</td>
                        <td>{{ $post->category?->name ?? '-' }}</td>
                        <td>{{ $post->author?->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $post->is_published ? 'success' : 'secondary' }}">
                                {{ $post->is_published ? 'Terbit' : 'Draft' }}
                            </span>
                        </td>
                        <td>{{ $post->published_at?->format('d M Y') ?? '-' }}</td>
                        <td>{{ number_format($post->views) }}</td>
                        <td>
                            @if($post->is_published)
                                <a href="{{ route('blog.show', $post) }}" target="_blank" class="btn btn-sm btn-info"><i class="bi bi-box-arrow-up-right"></i></a>
                            @endif
                            <a href="{{ route('admin.blog.posts.edit', $post) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.blog.posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus artikel ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada artikel</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $posts->links() }}
    </div>
</div>
@endsection
