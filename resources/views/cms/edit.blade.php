@extends('layouts.admin')

@section('title', 'Edit CMS — ' . ucfirst($pageContent->section))

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/lib/codemirror.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/theme/material-darker.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
<style>
    .cms-help { background: #f1f5f9; border-left: 3px solid #2563eb; padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.82rem; color: #475569; }
    .cms-help code { background: #fff; padding: 1px 5px; border-radius: 4px; color: #1d4ed8; font-size: 0.78rem; }
    .field-label-required::after { content: ' *'; color: #ef4444; }
    /* Quill custom */
    .ql-editor { min-height: 140px; font-size: 0.92rem; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }
    .ql-toolbar.ql-snow { border-radius: 8px 8px 0 0; border-color: #e2e8f0; background: #f8fafc; }
    .ql-container.ql-snow { border-radius: 0 0 8px 8px; border-color: #e2e8f0; }
    /* CodeMirror custom */
    .CodeMirror { border-radius: 8px; height: 360px; font-family: 'Courier New', monospace; font-size: 0.84rem; border: 1px solid #1e293b; }
    .CodeMirror-focused { box-shadow: 0 0 0 0.2rem rgba(37,99,235,0.25); border-color: #2563eb; }
    .editor-status { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 50px; font-size: 0.7rem; font-weight: 600; }
    .editor-status.valid { background: rgba(16,185,129,0.1); color: #059669; }
    .editor-status.invalid { background: rgba(239,68,68,0.1); color: #dc2626; }

    /* ─── Drag-Drop Zone ─── */
    .dropzone {
        position: relative;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 2.5rem 1.5rem;
        text-align: center;
        background: #fff;
        transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
        cursor: pointer;
        overflow: hidden;
    }
    .dropzone:hover {
        border-color: #2563eb;
        background: #eff6ff;
    }
    .dropzone.drag-over {
        border-color: #2563eb;
        background: #dbeafe;
        transform: scale(1.01);
    }
    .dropzone.drag-over::before {
        content: '📥 Lepas di sini';
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        background: rgba(37, 99, 235, 0.92);
        color: #fff; font-weight: 700; font-size: 1.4rem;
        z-index: 5;
        animation: dzPulse 0.6s ease-in-out infinite alternate;
    }
    @keyframes dzPulse { from { background: rgba(37,99,235,0.85); } to { background: rgba(6,182,212,0.92); } }
    .dropzone-icon { font-size: 2.5rem; color: #94a3b8; margin-bottom: 0.5rem; }
    .dropzone:hover .dropzone-icon { color: #2563eb; }
    .dropzone-title { font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-bottom: 0.25rem; }
    .dropzone-desc { font-size: 0.78rem; color: #64748b; }
    .dropzone-input { position: absolute; inset: 0; opacity: 0; cursor: pointer; }

    /* ─── Cropper Modal ─── */
    #cropperModal .modal-dialog { max-width: 800px; }
    #cropper-img-source { max-width: 100%; display: block; }
    .cropper-toolbar { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; padding: 0.75rem; background: #f8fafc; border-radius: 8px; margin-bottom: 1rem; }
    .cropper-toolbar .btn { font-size: 0.78rem; padding: 0.35rem 0.7rem; }
    .ratio-pills { display: flex; gap: 6px; flex-wrap: wrap; }
    .ratio-pill {
        padding: 0.35rem 0.85rem; border-radius: 50px;
        background: #fff; border: 1px solid #e2e8f0;
        font-size: 0.75rem; font-weight: 600; color: #475569;
        cursor: pointer; transition: all 0.2s;
    }
    .ratio-pill:hover { border-color: #2563eb; color: #2563eb; }
    .ratio-pill.active { background: #2563eb; color: #fff; border-color: #2563eb; }

    /* ─── Cropped Preview Card ─── */
    .crop-preview-card {
        display: flex; gap: 12px; align-items: center;
        padding: 0.75rem; background: #ecfdf5;
        border: 1px solid #a7f3d0; border-radius: 10px;
        margin-top: 0.75rem;
    }
    .crop-preview-card img {
        max-width: 120px; max-height: 80px;
        border-radius: 6px; background: #fff;
        object-fit: contain;
        border: 1px solid #d1fae5;
    }
</style>
@endpush

@php
    $sectionLabels = [
        'branding' => 'Branding (Logo & Nama Brand)',
        'hero' => 'Hero — Banner Utama',
        'trust' => 'Trust Strip — Badge Kompatibilitas',
        'video' => 'Video Demo',
        'modules' => 'Modul Lengkap (13 Card)',
        'features' => 'Fitur Unggulan (8 Card)',
        'showcase' => 'Screenshot Gallery',
        'stats' => 'Stats Banner',
        'testimonials' => 'Testimoni',
        'insights' => 'Insights / Blog',
        'partners' => 'Partner Logos',
        'cta' => 'Call to Action',
        'footer' => 'Footer',
        'about' => 'About',
    ];
    $sectionDescriptions = [
        'branding' => 'Nama brand, tagline, dan kontak WhatsApp default. Title = nama brand yang tampil di nav & footer.',
        'hero' => 'Banner utama paling atas. Title = headline besar. Content = paragraph deskripsi. button_text/url = tombol primary. meta.cta_secondary = tombol kedua.',
        'trust' => 'Strip badge di bawah hero. Edit array meta.badges (tiap item: icon, text).',
        'video' => 'Section demo video. video_url = link YouTube. image_url = poster sebelum diputar. meta.highlights = chip di bawah video.',
        'modules' => 'Grid 13 modul. Edit array meta.items (tiap item: icon, color [grad-blue/teal/emerald/amber/rose/indigo/cyan/slate], title, desc).',
        'features' => 'Grid 8 fitur unggulan. Edit array meta.items (tiap item: icon, color hex, title, desc).',
        'showcase' => 'Section dark dengan 3 screenshot. Edit array meta.items (img URL, title, desc).',
        'stats' => 'Banner gradient angka stats. Items pakai value <code>auto:patients</code>/<code>auto:doctors</code>/<code>auto:polys</code> untuk auto-fetch dari DB, atau text manual.',
        'testimonials' => 'Quote dari klien. Edit array meta.items (quote, avatar URL, name, role).',
        'insights' => 'Article cards untuk SEO. Edit array meta.items (tag, title, excerpt, img URL, duration, url).',
        'partners' => 'Logo partner sebagai text. Edit array meta.items (string array nama partner).',
        'cta' => 'Banner Call To Action paling bawah. button_* = tombol primary, meta.cta_secondary_* = tombol kedua.',
        'footer' => 'Footer dengan 4 kolom. meta berisi: address, phone, email, cert_badges, social.',
        'about' => 'Section about (legacy, optional).',
    ];
    $label = $sectionLabels[$pageContent->section] ?? ucfirst($pageContent->section);
    $desc = $sectionDescriptions[$pageContent->section] ?? '';
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3 flex-wrap">
    <div>
        <a href="{{ route('cms.index') }}" class="text-muted small text-decoration-none">
            <i class="bi bi-arrow-left"></i> Kembali ke daftar CMS
        </a>
        <h1 class="h2 mb-1 mt-2">Edit: {{ $label }}</h1>
        <code class="small text-muted">section = {{ $pageContent->section }}</code>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-box-arrow-up-right"></i> Preview Halaman
        </a>
    </div>
</div>

@if($desc)
    <div class="cms-help mb-4">
        <i class="bi bi-info-circle me-1"></i> <strong>Cara pakai:</strong> {!! $desc !!}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('cms.update', $pageContent) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            {{-- TITLE --}}
            <div class="mb-3">
                <label class="form-label fw-semibold field-label-required">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $pageContent->title) }}" required maxlength="255">
                <small class="text-muted">Judul utama section ini.</small>
            </div>

            {{-- SUBTITLE --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Subtitle</label>
                <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $pageContent->subtitle) }}" maxlength="255">
                <small class="text-muted">Label kecil di atas title (opsional).</small>
            </div>

            {{-- CONTENT (Quill Rich Text) --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Content / Deskripsi</label>
                <div id="quill-editor"></div>
                <textarea name="content" id="content-input" hidden>{{ old('content', $pageContent->content) }}</textarea>
                <small class="text-muted">Paragraf penjelasan section. Pakai toolbar untuk format teks (bold, italic, link, list, dll).</small>
            </div>

            {{-- IMAGE: Upload File ATAU URL eksternal --}}
            @if(in_array($pageContent->section, ['hero', 'video', 'branding']))
                <div class="mb-4 p-3 border rounded" style="background:#f8fafc;">
                    <label class="form-label fw-semibold mb-2">
                        @if($pageContent->section === 'branding')
                            <i class="bi bi-image-fill text-primary"></i> Logo Brand
                        @elseif($pageContent->section === 'hero')
                            <i class="bi bi-image-fill text-primary"></i> Gambar Hero
                        @else
                            <i class="bi bi-image-fill text-primary"></i> Poster Video
                        @endif
                    </label>

                    {{-- Preview gambar saat ini --}}
                    @if($pageContent->image_url)
                        <div class="mb-3 d-flex align-items-center gap-3 p-2 bg-white border rounded">
                            <img src="{{ $pageContent->image_url }}" alt="Preview" style="max-width:160px;max-height:90px;border-radius:6px;border:1px solid #e2e8f0;object-fit:contain;background:#fff;">
                            <div class="flex-grow-1">
                                <div class="small fw-semibold text-success"><i class="bi bi-check-circle"></i> Gambar terpasang</div>
                                <div class="small text-muted text-break" style="font-size:0.72rem;">{{ $pageContent->image_url }}</div>
                                <label class="form-check small mt-1">
                                    <input type="checkbox" name="remove_image" value="1" class="form-check-input"> Hapus gambar saat simpan
                                </label>
                            </div>
                        </div>
                    @endif

                    {{-- Tab Pilihan: Upload File ATAU URL --}}
                    <ul class="nav nav-pills mb-2 small" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active py-1 px-3" data-bs-toggle="tab" data-bs-target="#tab-upload">
                                <i class="bi bi-cloud-arrow-up-fill"></i> Upload dari Komputer
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link py-1 px-3" data-bs-toggle="tab" data-bs-target="#tab-url">
                                <i class="bi bi-link-45deg"></i> URL Eksternal
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- Tab 1: Upload File dengan Drag-Drop + Cropper --}}
                        <div class="tab-pane fade show active" id="tab-upload">
                            <div class="dropzone" id="dropzone">
                                <input type="file" name="image_file" id="image-file-input" class="dropzone-input" accept="image/jpeg,image/png,image/webp,image/svg+xml,image/gif">
                                <div class="dropzone-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                                <div class="dropzone-title">Drag &amp; drop file di sini</div>
                                <div class="dropzone-desc">atau klik untuk browse — JPG, PNG, WEBP, SVG, GIF (maks 2 MB)</div>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-crop"></i>
                                Setelah pilih file, akan muncul tools <strong>crop &amp; rotate</strong>.
                                Klik "Simpan Crop" untuk pakai hasil edit.
                            </small>
                            <div id="upload-preview"></div>
                        </div>

                        {{-- Tab 2: URL Eksternal --}}
                        <div class="tab-pane fade" id="tab-url">
                            <input type="url" name="image_url" class="form-control" value="{{ old('image_url', $pageContent->image_url) }}" maxlength="500" placeholder="https://images.unsplash.com/...">
                            <small class="text-muted">URL gambar dari sumber eksternal (mis. Unsplash, Cloudinary, hosting Anda).</small>
                        </div>
                    </div>
                </div>
            @endif

            {{-- BUTTON FIELDS --}}
            @if(in_array($pageContent->section, ['hero', 'cta']))
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Button Text (Primary)</label>
                        <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $pageContent->button_text) }}" maxlength="100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Button URL</label>
                        <input type="text" name="button_url" class="form-control" value="{{ old('button_url', $pageContent->button_url) }}" maxlength="500" placeholder="#video atau /register atau https://...">
                    </div>
                </div>
            @endif

            {{-- VIDEO URL --}}
            @if($pageContent->section === 'video')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Video URL (YouTube)</label>
                    <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $pageContent->video_url) }}" maxlength="500" placeholder="https://www.youtube.com/watch?v=XXX">
                    <small class="text-muted">URL lengkap video YouTube. Sistem akan auto-extract video ID.</small>
                </div>
            @endif

            {{-- META JSON EDITOR (CodeMirror) --}}
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label fw-semibold mb-0">
                        Meta Data (JSON)
                        <span class="badge bg-info ms-1" style="font-size:0.65rem;">Advanced</span>
                    </label>
                    <span id="json-status" class="editor-status valid"><i class="bi bi-check-circle"></i> Valid</span>
                </div>
                <textarea name="meta_json" id="json-input" hidden>{{ old('meta_json', json_encode($pageContent->meta ?? new \stdClass(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) }}</textarea>
                <div id="codemirror-container"></div>
                <small class="text-muted mt-1 d-block">
                    <i class="bi bi-info-circle"></i>
                    Editor JSON dengan syntax highlighting + auto-validate. Edit array repeating items (modules, features, testimonials, dll.).
                    Tekan <kbd>Ctrl</kbd>+<kbd>Shift</kbd>+<kbd>F</kbd> untuk auto-format.
                </small>
            </div>

            {{-- ORDER + ACTIVE --}}
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Order</label>
                    <input type="number" name="order" class="form-control" value="{{ old('order', $pageContent->order) }}" min="0" max="999">
                    <small class="text-muted">Urutan tampil.</small>
                </div>
                <div class="col-md-9">
                    <label class="form-label fw-semibold">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input" {{ old('is_active', $pageContent->is_active) ? 'checked' : '' }} style="width:3rem;height:1.5rem;">
                        <label for="is_active" class="form-check-label ms-2">
                            <span class="text-success fw-semibold">Aktif</span> — section ini tampil di halaman utama
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light d-flex gap-2 justify-content-end">
            <a href="{{ route('cms.index') }}" class="btn btn-light">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan &amp; Tayangkan
            </button>
        </div>
    </div>
</form>

{{-- ============ CROPPER MODAL ============ --}}
<div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-crop"></i> Crop &amp; Edit Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="cropper-toolbar">
                    <div class="ratio-pills" id="ratio-pills">
                        <button type="button" class="ratio-pill active" data-ratio="NaN">Bebas</button>
                        <button type="button" class="ratio-pill" data-ratio="1">1:1</button>
                        <button type="button" class="ratio-pill" data-ratio="1.7777">16:9</button>
                        <button type="button" class="ratio-pill" data-ratio="1.3333">4:3</button>
                        <button type="button" class="ratio-pill" data-ratio="0.75">3:4</button>
                        <button type="button" class="ratio-pill" data-ratio="2.5">Banner</button>
                    </div>
                </div>
                <div class="cropper-toolbar">
                    <button type="button" class="btn btn-outline-secondary" id="cropper-rotate-left" title="Rotate kiri 90°"><i class="bi bi-arrow-counterclockwise"></i></button>
                    <button type="button" class="btn btn-outline-secondary" id="cropper-rotate-right" title="Rotate kanan 90°"><i class="bi bi-arrow-clockwise"></i></button>
                    <button type="button" class="btn btn-outline-secondary" id="cropper-flip-h" title="Flip horizontal"><i class="bi bi-symmetry-vertical"></i></button>
                    <button type="button" class="btn btn-outline-secondary" id="cropper-flip-v" title="Flip vertical"><i class="bi bi-symmetry-horizontal"></i></button>
                    <button type="button" class="btn btn-outline-secondary" id="cropper-zoom-in" title="Zoom in"><i class="bi bi-zoom-in"></i></button>
                    <button type="button" class="btn btn-outline-secondary" id="cropper-zoom-out" title="Zoom out"><i class="bi bi-zoom-out"></i></button>
                    <button type="button" class="btn btn-outline-secondary" id="cropper-reset" title="Reset"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                </div>
                <div style="max-height:60vh;overflow:hidden;">
                    <img id="cropper-img-source" alt="Source untuk crop">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <small class="text-muted"><i class="bi bi-info-circle"></i> Geser kotak crop, drag corner untuk resize, atau pilih ratio di atas.</small>
                <div>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="cropper-save"><i class="bi bi-check-lg"></i> Simpan Crop</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/lib/codemirror.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/mode/javascript/javascript.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/addon/edit/closebrackets.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/addon/edit/matchbrackets.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.16/addon/lint/lint.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ─── Quill Rich Text Editor ───
        var contentInput = document.getElementById('content-input');
        var quillContainer = document.getElementById('quill-editor');
        if (quillContainer && contentInput) {
            var quill = new Quill(quillContainer, {
                theme: 'snow',
                placeholder: 'Tulis deskripsi section di sini…',
                modules: {
                    toolbar: [
                        [{ header: [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ color: [] }, { background: [] }],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link', 'blockquote'],
                        [{ align: [] }],
                        ['clean']
                    ]
                }
            });
            // Inisialisasi dari textarea
            quill.root.innerHTML = contentInput.value || '';
            // Sync ke hidden textarea sebelum submit
            quill.on('text-change', function () {
                contentInput.value = quill.root.innerHTML;
            });
        }

        // ─── CodeMirror JSON Editor ───
        var jsonInput = document.getElementById('json-input');
        var cmContainer = document.getElementById('codemirror-container');
        var jsonStatus = document.getElementById('json-status');
        if (cmContainer && jsonInput) {
            var cm = CodeMirror(cmContainer, {
                value: jsonInput.value || '{}',
                mode: { name: 'javascript', json: true },
                theme: 'material-darker',
                lineNumbers: true,
                lineWrapping: true,
                autoCloseBrackets: true,
                matchBrackets: true,
                indentUnit: 2,
                tabSize: 2,
                extraKeys: {
                    'Ctrl-Shift-F': function (editor) {
                        try {
                            var parsed = JSON.parse(editor.getValue());
                            editor.setValue(JSON.stringify(parsed, null, 2));
                        } catch (e) {
                            alert('Format JSON tidak valid: ' + e.message);
                        }
                    }
                }
            });

            function validate() {
                var v = cm.getValue().trim();
                if (v === '' || v === '{}' || v === 'null') {
                    jsonStatus.className = 'editor-status valid';
                    jsonStatus.innerHTML = '<i class="bi bi-check-circle"></i> Valid';
                    jsonInput.value = v;
                    return true;
                }
                try {
                    JSON.parse(v);
                    jsonStatus.className = 'editor-status valid';
                    jsonStatus.innerHTML = '<i class="bi bi-check-circle"></i> Valid';
                    jsonInput.value = v;
                    return true;
                } catch (e) {
                    jsonStatus.className = 'editor-status invalid';
                    jsonStatus.innerHTML = '<i class="bi bi-x-circle"></i> ' + e.message;
                    return false;
                }
            }

            cm.on('change', function () {
                jsonInput.value = cm.getValue();
                validate();
            });

            // Initial validate
            validate();

            // ─── Drag-Drop + Cropper ───
            var fileInput = document.getElementById('image-file-input');
            var dropzone = document.getElementById('dropzone');
            var preview = document.getElementById('upload-preview');
            var cropperImg = document.getElementById('cropper-img-source');
            var modalEl = document.getElementById('cropperModal');
            var cropperModal = modalEl ? new bootstrap.Modal(modalEl) : null;
            var cropperInstance = null;
            var croppedBlob = null;
            var originalFileName = 'image.png';

            // Drag-drop handlers
            if (dropzone) {
                ['dragenter', 'dragover'].forEach(function (ev) {
                    dropzone.addEventListener(ev, function (e) {
                        e.preventDefault(); e.stopPropagation();
                        dropzone.classList.add('drag-over');
                    });
                });
                ['dragleave', 'drop'].forEach(function (ev) {
                    dropzone.addEventListener(ev, function (e) {
                        e.preventDefault(); e.stopPropagation();
                        dropzone.classList.remove('drag-over');
                    });
                });
                dropzone.addEventListener('drop', function (e) {
                    var files = e.dataTransfer?.files;
                    if (files && files.length > 0) {
                        handleFile(files[0]);
                    }
                });
            }

            if (fileInput) {
                fileInput.addEventListener('change', function (e) {
                    var file = e.target.files[0];
                    if (file) handleFile(file);
                });
            }

            function handleFile(file) {
                // Validasi tipe
                if (! file.type.startsWith('image/')) {
                    preview.innerHTML = '<div class="text-danger small mt-2"><i class="bi bi-x-circle"></i> File harus gambar.</div>';
                    return;
                }
                // Validasi ukuran
                if (file.size > 2 * 1024 * 1024) {
                    preview.innerHTML = '<div class="text-danger small mt-2"><i class="bi bi-x-circle"></i> File terlalu besar (maks 2 MB). File: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB</div>';
                    fileInput.value = '';
                    return;
                }

                originalFileName = file.name;

                // SVG: tidak perlu crop, langsung pakai
                if (file.type === 'image/svg+xml') {
                    croppedBlob = file;
                    showFinalPreview(file, false);
                    return;
                }

                // Open cropper modal
                var reader = new FileReader();
                reader.onload = function (ev) {
                    cropperImg.src = ev.target.result;
                    cropperModal.show();
                };
                reader.readAsDataURL(file);
            }

            // Init cropper saat modal terbuka
            if (modalEl) {
                modalEl.addEventListener('shown.bs.modal', function () {
                    if (cropperInstance) cropperInstance.destroy();
                    cropperInstance = new Cropper(cropperImg, {
                        viewMode: 1,
                        autoCropArea: 0.95,
                        background: true,
                        movable: true,
                        zoomable: true,
                        rotatable: true,
                        scalable: true,
                    });
                });
                modalEl.addEventListener('hidden.bs.modal', function () {
                    if (cropperInstance) { cropperInstance.destroy(); cropperInstance = null; }
                });
            }

            // Toolbar handlers
            document.querySelectorAll('#ratio-pills .ratio-pill').forEach(function (pill) {
                pill.addEventListener('click', function () {
                    document.querySelectorAll('#ratio-pills .ratio-pill').forEach(function (p) { p.classList.remove('active'); });
                    pill.classList.add('active');
                    if (! cropperInstance) return;
                    var ratio = parseFloat(pill.dataset.ratio);
                    cropperInstance.setAspectRatio(isNaN(ratio) ? NaN : ratio);
                });
            });

            var btnRotateL = document.getElementById('cropper-rotate-left');
            var btnRotateR = document.getElementById('cropper-rotate-right');
            var btnFlipH = document.getElementById('cropper-flip-h');
            var btnFlipV = document.getElementById('cropper-flip-v');
            var btnZoomIn = document.getElementById('cropper-zoom-in');
            var btnZoomOut = document.getElementById('cropper-zoom-out');
            var btnReset = document.getElementById('cropper-reset');
            var btnSave = document.getElementById('cropper-save');
            var flipState = { x: 1, y: 1 };

            if (btnRotateL) btnRotateL.addEventListener('click', function () { cropperInstance?.rotate(-90); });
            if (btnRotateR) btnRotateR.addEventListener('click', function () { cropperInstance?.rotate(90); });
            if (btnFlipH) btnFlipH.addEventListener('click', function () { flipState.x *= -1; cropperInstance?.scaleX(flipState.x); });
            if (btnFlipV) btnFlipV.addEventListener('click', function () { flipState.y *= -1; cropperInstance?.scaleY(flipState.y); });
            if (btnZoomIn) btnZoomIn.addEventListener('click', function () { cropperInstance?.zoom(0.1); });
            if (btnZoomOut) btnZoomOut.addEventListener('click', function () { cropperInstance?.zoom(-0.1); });
            if (btnReset) btnReset.addEventListener('click', function () { cropperInstance?.reset(); flipState = { x: 1, y: 1 }; });

            // Save crop → convert ke blob → ganti file di FormData saat submit
            if (btnSave) {
                btnSave.addEventListener('click', function () {
                    if (! cropperInstance) return;
                    var canvas = cropperInstance.getCroppedCanvas({
                        maxWidth: 2400,
                        maxHeight: 2400,
                        imageSmoothingQuality: 'high',
                    });
                    canvas.toBlob(function (blob) {
                        if (! blob) return;
                        croppedBlob = blob;
                        var url = URL.createObjectURL(blob);
                        showFinalPreview({ name: originalFileName, size: blob.size, type: blob.type }, true, url);
                        cropperModal.hide();
                    }, 'image/png', 0.92);
                });
            }

            function showFinalPreview(fileInfo, cropped, blobUrl) {
                var url = blobUrl;
                if (! url && fileInfo instanceof File) {
                    url = URL.createObjectURL(fileInfo);
                }
                preview.innerHTML = '<div class="crop-preview-card">'
                    + '<img src="' + url + '" alt="Preview hasil crop">'
                    + '<div class="small flex-grow-1">'
                    +   '<div class="fw-bold text-success"><i class="bi bi-check-circle-fill"></i> ' + (cropped ? 'Hasil Crop Siap Disimpan' : 'File Siap Disimpan') + '</div>'
                    +   '<div class="text-muted">' + fileInfo.name + ' — ' + (fileInfo.size / 1024).toFixed(1) + ' KB</div>'
                    +   (cropped ? '<button type="button" class="btn btn-sm btn-outline-primary mt-1" id="reedit-crop"><i class="bi bi-crop"></i> Edit Lagi</button>' : '')
                    + '</div>'
                    + '</div>';

                var reedit = document.getElementById('reedit-crop');
                if (reedit) {
                    reedit.addEventListener('click', function () {
                        cropperImg.src = url;
                        cropperModal.show();
                    });
                }
            }

            // Saat submit form, replace file di FormData dengan cropped blob
            var cmsForm = document.querySelector('form[action*="cms"]');
            if (cmsForm) {
                cmsForm.addEventListener('submit', function (e) {
                    if (croppedBlob) {
                        // Buat DataTransfer untuk replace files di input
                        try {
                            var dt = new DataTransfer();
                            var ext = croppedBlob.type === 'image/svg+xml' ? 'svg' : 'png';
                            var fname = (originalFileName.replace(/\.[^.]+$/, '') || 'cropped') + '-cropped.' + ext;
                            dt.items.add(new File([croppedBlob], fname, { type: croppedBlob.type }));
                            fileInput.files = dt.files;
                        } catch (err) {
                            console.warn('DataTransfer not supported, sending original file');
                        }
                    }
                });
            }

            // Block submit kalau JSON invalid
            var form = document.querySelector('form[action*="cms"]');
            if (form) {
                form.addEventListener('submit', function (e) {
                    if (! validate()) {
                        e.preventDefault();
                        alert('Format JSON di field Meta Data tidak valid. Perbaiki dulu sebelum simpan.');
                    }
                });
            }
        }
    });
</script>
@endsection
