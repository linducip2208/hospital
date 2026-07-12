@extends('layouts.admin')

@section('title', 'Blog — Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Blog — Kategori</h1>
    <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Artikel</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="row g-3">
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Tambah Kategori</div>
            <div class="card-body">
                <form action="{{ route('admin.blog.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <button class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Tambah</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr><th>Nama</th><th>Slug</th><th>Artikel</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $cat)
                            <tr>
                                <td>{{ $cat->name }}</td>
                                <td><code>{{ $cat->slug }}</code></td>
                                <td>{{ $cat->posts_count }}</td>
                                <td>
                                    <form action="{{ route('admin.blog.categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada kategori</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
