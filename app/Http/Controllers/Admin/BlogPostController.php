<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Services\Seo\IndexNowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function index(Request $request): View
    {
        $query = BlogPost::with('category', 'author')->latest();

        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        $posts = $query->paginate(15)->withQueryString();

        return view('admin.blog.posts.index', compact('posts'));
    }

    public function create(): View
    {
        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.blog.posts.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);
        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? $validated['title']);
        $validated['author_id'] = $request->user()->id;
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $validated['published_at'] ?? ($validated['is_published'] ? now() : null);

        $post = BlogPost::create($validated);

        $this->pingIndexNow($post);

        return redirect()->route('admin.blog.posts.index')->with('success', 'Artikel berhasil dibuat.');
    }

    public function edit(BlogPost $post): View
    {
        $categories = BlogCategory::orderBy('name')->get();

        return view('admin.blog.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, BlogPost $post): RedirectResponse
    {
        $validated = $this->validateData($request, $post->id);
        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? $validated['title'], $post->id);
        $validated['is_published'] = $request->boolean('is_published');
        if ($validated['is_published'] && ! $post->published_at) {
            $validated['published_at'] = $validated['published_at'] ?? now();
        }

        $post->update($validated);

        $this->pingIndexNow($post);

        return redirect()->route('admin.blog.posts.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(BlogPost $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.blog.posts.index')->with('success', 'Artikel berhasil dihapus.');
    }

    protected function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|url|max:500',
            'category_id' => 'nullable|exists:blog_categories,id',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
        ]);
    }

    protected function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);
        $base = $slug;
        $i = 1;
        while (BlogPost::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    protected function pingIndexNow(BlogPost $post): void
    {
        if (! $post->is_published || $post->published_at > now()) {
            return;
        }

        try {
            app(IndexNowService::class)->submitSingle(route('blog.show', $post));
        } catch (\Throwable $e) {
            // silent — non-critical
        }
    }
}
