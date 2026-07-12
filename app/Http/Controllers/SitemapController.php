<?php

namespace App\Http\Controllers;

use App\Services\Seo\SitemapBuilder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function __construct(protected SitemapBuilder $builder) {}

    /** Sitemap index: /sitemap.xml */
    public function index(): Response
    {
        $xml = Cache::remember('sitemap.index', now()->addHours(24), function () {
            $base = rtrim(config('app.url'), '/');
            $groups = $this->builder->index();

            $out = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $out .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
            foreach ($groups as $group) {
                $out .= "  <sitemap><loc>{$base}/sitemap-{$group}.xml</loc><lastmod>".now()->toAtomString()."</lastmod></sitemap>\n";
            }
            $out .= '</sitemapindex>';

            return $out;
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /** Sitemap per grup: /sitemap-{group}.xml */
    public function group(string $group): Response
    {
        $xml = Cache::remember("sitemap.group.{$group}", now()->addHours(24), function () use ($group) {
            $urls = $this->builder->urlsForGroup($group);
            abort_if(empty($urls), 404);

            $out = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
            $out .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
            foreach ($urls as $u) {
                $loc = htmlspecialchars($u['loc'], ENT_XML1);
                $priority = $u['priority'] ?? '0.5';
                $out .= "  <url><loc>{$loc}</loc><changefreq>weekly</changefreq><priority>{$priority}</priority></url>\n";
            }
            $out .= '</urlset>';

            return $out;
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /** robots.txt dinamis */
    public function robots(): Response
    {
        $base = rtrim(config('app.url'), '/');
        $lines = [
            'User-agent: *',
            'Allow: /$',
            'Allow: /docs',
            'Allow: /blog',
            'Allow: /best-',
            'Allow: /alternatif-',
            'Allow: /bandingkan/',
            'Allow: /fitur-',
            'Allow: /aplikasi-',
            'Allow: /beli-',
            'Disallow: /dashboard',
            'Disallow: /settings',
            'Disallow: /cms',
            'Disallow: /__pair',
            'Disallow: /api',
            "Sitemap: {$base}/sitemap.xml",
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
