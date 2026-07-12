<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_index_is_valid_xml(): void
    {
        $res = $this->get('/sitemap.xml');
        $res->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $this->assertStringContainsString('<sitemapindex', $res->getContent());
    }

    public function test_sitemap_group_renders(): void
    {
        $this->get('/sitemap-pseo-best.xml')
            ->assertOk()
            ->assertSee('<urlset', false);
    }

    public function test_robots_txt(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap:');
    }

    public function test_pseo_best_page(): void
    {
        $this->get('/best-rumah-sakit-jakarta')->assertOk()->assertSee('Jakarta');
    }

    public function test_pseo_alternative_page(): void
    {
        $this->get('/alternatif-khanza')->assertOk();
    }

    public function test_pseo_compare_page(): void
    {
        $this->get('/bandingkan/khanza-vs-trustmedis')->assertOk();
    }

    public function test_pseo_feature_page(): void
    {
        $this->get('/fitur-rekam-medis-elektronik')->assertOk();
    }

    public function test_pseo_source_code_page(): void
    {
        $this->get('/beli-source-code-simrs')->assertOk();
    }

    public function test_unknown_pseo_returns_404(): void
    {
        $this->get('/best-tidak-ada-kotaentah')->assertNotFound();
    }
}
