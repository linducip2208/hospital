<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = BlogPost::published()->with('category', 'author')->latest('published_at');

        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(9)->withQueryString();
        $categories = BlogCategory::withCount(['posts' => fn ($q) => $q->published()])->get();
        $recent = BlogPost::published()->latest('published_at')->take(5)->get();

        return view('blog.index', compact('posts', 'categories', 'recent', 'search'));
    }

    public function category(BlogCategory $category): View
    {
        $posts = BlogPost::published()->byCategory($category->slug)
            ->with('category', 'author')->latest('published_at')->paginate(9);
        $categories = BlogCategory::withCount(['posts' => fn ($q) => $q->published()])->get();
        $recent = BlogPost::published()->latest('published_at')->take(5)->get();

        return view('blog.category', compact('category', 'posts', 'categories', 'recent'));
    }

    public function show(BlogPost $post): View
    {
        abort_unless($post->is_published && $post->published_at <= now(), 404);

        $post->increment('views');

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->latest('published_at')->take(3)->get();

        $categories = BlogCategory::withCount(['posts' => fn ($q) => $q->published()])->get();
        $recent = BlogPost::published()->where('id', '!=', $post->id)->latest('published_at')->take(5)->get();

        return view('blog.show', compact('post', 'related', 'categories', 'recent'));
    }

    public function feed(): Response
    {
        $posts = BlogPost::published()->with('author')->latest('published_at')->take(30)->get();
        $content = view('blog.feed', compact('posts'))->render();

        return response($content, 200, ['Content-Type' => 'application/rss+xml; charset=UTF-8']);
    }
}
