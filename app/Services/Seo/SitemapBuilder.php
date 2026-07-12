<?php

namespace App\Services\Seo;

use App\Models\BlogPost;
use App\Support\SeoData;

class SitemapBuilder
{
    /** Maksimum URL per file sitemap (~2MB / ~50rb URL). */
    const MAX_URLS_PER_SITEMAP = 50000;

    /** Daftar nama grup sitemap, dengan chunk suffix untuk grup > 50rb URL. */
    public function index(): array
    {
        $groups = [];
        foreach ($this->patternGroups() as $group => $estimate) {
            $chunks = max(1, (int) ceil($estimate / self::MAX_URLS_PER_SITEMAP));
            if ($chunks <= 1) {
                $groups[] = $group;
            } else {
                for ($i = 1; $i <= $chunks; $i++) {
                    $groups[] = $group.'-'.$i;
                }
            }
        }

        return $groups;
    }

    /** Definisi grup pola + estimasi jumlah URL. */
    protected function patternGroups(): array
    {
        $cityCount = count(SeoData::CITIES);
        $facilityCount = count(SeoData::FACILITY_TYPES);
        $featureCount = count(SeoData::FEATURES);
        $specialtyCount = count(SeoData::SPECIALTIES);
        $scaleCount = count(SeoData::SCALES);
        $kwCount = count(SeoData::SOURCE_CODE_KEYWORDS);
        $years = SeoData::YEARS_AHEAD + 1;

        return [
            'pages' => 8,
            'blogs' => max(1, BlogPost::published()->count()) + 2,
            'pseo-best' => $facilityCount * $cityCount,
            'pseo-best-year' => $facilityCount * $cityCount * $years,
            'pseo-alternative' => count(SeoData::ALTERNATIVES),
            'pseo-compare' => count(SeoData::allCompareUrls()),
            'pseo-feature' => $featureCount,
            'pseo-feature-city' => $featureCount * $cityCount,
            'pseo-facility-city' => $facilityCount * $cityCount,
            'pseo-specialty-city' => $specialtyCount * $cityCount,
            'sc-base' => $kwCount,
            'sc-city' => $kwCount * $cityCount,
            'sc-scale' => $kwCount * $scaleCount,
            'sc-feature' => $kwCount * $featureCount,
            'sc-city-scale' => $kwCount * $cityCount * $scaleCount,
        ];
    }

    /** URL untuk grup tertentu (dengan chunking). */
    public function urlsForGroup(string $group): array
    {
        $chunk = 1;
        if (preg_match('/^(.+)-(\d+)$/', $group, $m) && ! $this->isBaseGroup($group)) {
            $group = $m[1];
            $chunk = (int) $m[2];
        }

        $allUrls = $this->rawUrlsForGroup($group);

        if ($chunk > 1) {
            $offset = ($chunk - 1) * self::MAX_URLS_PER_SITEMAP;
            $allUrls = array_slice($allUrls, $offset, self::MAX_URLS_PER_SITEMAP);
        } elseif (count($allUrls) > self::MAX_URLS_PER_SITEMAP) {
            $allUrls = array_slice($allUrls, 0, self::MAX_URLS_PER_SITEMAP);
        }

        return $allUrls;
    }

    protected function isBaseGroup(string $group): bool
    {
        return array_key_exists($group, $this->patternGroups());
    }

    protected function rawUrlsForGroup(string $group): array
    {
        $base = rtrim(config('app.url'), '/');

        $urls = match ($group) {
            'pages' => [
                ['loc' => "$base/", 'priority' => '1.0'],
                ['loc' => "$base/docs", 'priority' => '0.8'],
                ['loc' => "$base/blog", 'priority' => '0.8'],
                ['loc' => "$base/blog/feed.xml", 'priority' => '0.4'],
                ['loc' => "$base/beli-aplikasi-rumah-sakit", 'priority' => '0.9'],
                ['loc' => "$base/beli-source-code-simrs", 'priority' => '0.9'],
                ['loc' => "$base/login", 'priority' => '0.3'],
                ['loc' => "$base/register", 'priority' => '0.3'],
            ],
            'blogs' => $this->blogUrls($base),
            'pseo-best' => $this->map($base, SeoData::allBestUrls(), '0.7'),
            'pseo-best-year' => $this->map($base, SeoData::allBestYearUrls(), '0.6'),
            'pseo-alternative' => $this->map($base, SeoData::allAlternativeUrls(), '0.7'),
            'pseo-compare' => $this->map($base, SeoData::allCompareUrls(), '0.5'),
            'pseo-feature' => $this->map($base, SeoData::allFeatureUrls(), '0.7'),
            'pseo-feature-city' => $this->map($base, SeoData::allFeatureCityUrls(), '0.5'),
            'pseo-facility-city' => $this->map($base, SeoData::allFacilityCityUrls(), '0.6'),
            'pseo-specialty-city' => $this->map($base, SeoData::allSpecialtyCityUrls(), '0.5'),
            'sc-base' => $this->map($base, SeoData::allSourceCodeUrls(), '0.8'),
            'sc-city' => $this->map($base, SeoData::allSourceCodeCityUrls(), '0.6'),
            'sc-scale' => $this->map($base, SeoData::allSourceCodeScaleUrls(), '0.5'),
            'sc-feature' => $this->map($base, SeoData::allSourceCodeFeatureUrls(), '0.5'),
            'sc-city-scale' => $this->map($base, SeoData::allSourceCodeCityScaleUrls(), '0.4'),
            default => [],
        };

        return $urls;
    }

    public function totalUrlCount(): int
    {
        return array_sum($this->patternGroups());
    }

    private function map(string $base, array $paths, string $priority): array
    {
        return array_map(fn ($p) => ['loc' => $base.$p, 'priority' => $priority], $paths);
    }

    private function blogUrls(string $base): array
    {
        $urls = [
            ['loc' => "$base/blog", 'priority' => '0.8'],
        ];
        foreach (BlogPost::published()->select('slug')->get() as $post) {
            $urls[] = ['loc' => "$base/blog/{$post->slug}", 'priority' => '0.7'];
        }

        return $urls;
    }
}
