@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $post->title ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug <small class="text-muted">(kosongkan untuk otomatis)</small></label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $post->slug ?? '') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Ringkasan (excerpt)</label>
                    <textarea name="excerpt" class="form-control" rows="2" maxlength="500">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                </div>
                <div class="mb-0">
                    <label class="form-label">Konten <span class="text-danger">*</span> <small class="text-muted">(HTML diperbolehkan)</small></label>
                    <textarea name="content" class="form-control" rows="14" required>{{ old('content', $post->content ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold">SEO</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $post->meta_title ?? '') }}" maxlength="255">
                </div>
                <div class="mb-0">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2" maxlength="300">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold">Publikasi</div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input type="checkbox" name="is_published" value="1" class="form-check-input" id="isPublished"
                        @checked(old('is_published', $post->is_published ?? false))>
                    <label class="form-check-label" for="isPublished">Terbitkan</label>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Terbit</label>
                    <input type="datetime-local" name="published_at" class="form-control"
                        value="{{ old('published_at', isset($post->published_at) ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div class="mb-0">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select">
                        <option value="">— Tanpa Kategori —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $post->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold">Gambar Utama</div>
            <div class="card-body">
                <label class="form-label">URL Gambar</label>
                <input type="url" name="featured_image" class="form-control" value="{{ old('featured_image', $post->featured_image ?? '') }}" placeholder="https://...">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Simpan Artikel</button>
    </div>
</div>
