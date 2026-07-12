<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_index_renders(): void
    {
        $this->get('/blog')->assertOk()->assertSee('Blog');
    }

    public function test_published_post_is_visible(): void
    {
        $cat = BlogCategory::create(['name' => 'Umum', 'slug' => 'umum']);
        $post = BlogPost::create([
            'title' => 'Artikel Uji Coba',
            'slug' => 'artikel-uji-coba',
            'content' => '<p>Konten uji.</p>',
            'excerpt' => 'Ringkasan',
            'category_id' => $cat->id,
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->get('/blog/'.$post->slug)->assertOk()->assertSee('Artikel Uji Coba');
        $this->get('/blog/category/'.$cat->slug)->assertOk();
    }

    public function test_draft_post_returns_404(): void
    {
        $post = BlogPost::create([
            'title' => 'Draft',
            'slug' => 'draft-artikel',
            'content' => 'x',
            'is_published' => false,
        ]);

        $this->get('/blog/'.$post->slug)->assertNotFound();
    }

    public function test_rss_feed_works(): void
    {
        $this->get('/blog/feed.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
