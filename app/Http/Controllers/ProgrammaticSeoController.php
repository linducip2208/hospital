<?php

namespace App\Http\Controllers;

use App\Services\Seo\ContentGenerator;
use App\Support\SeoData;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProgrammaticSeoController extends Controller
{
    public function __construct(protected ContentGenerator $content) {}

    /** /best-{slug}  → slug = {facility}-{city} atau {facility}-{city}-{year} */
    public function best(string $slug): View
    {
        [$facility, $facilityName, $rest] = $this->matchPrefix($slug, SeoData::FACILITY_TYPES);
        abort_if(! $facilityName, 404);

        $year = null;
        if (preg_match('/-(\d{4})$/', $rest, $m)) {
            $year = $m[1];
            abort_unless((int) $year >= 2024 && (int) $year <= (int) date('Y') + SeoData::YEARS_AHEAD, 404);
            $rest = trim(Str::beforeLast($rest, "-{$year}"), '-');
        }

        $cityName = SeoData::cityName($rest);
        abort_if(! $cityName, 404);

        $yearSuffix = $year ? " {$year}" : '';
        $headline = "Rekomendasi {$facilityName} Terbaik di {$cityName}{$yearSuffix}";
        $bullets = array_slice(array_values(SeoData::FEATURES), 0, 8);

        return view('pseo.listing', [
            'headline' => $headline,
            'kicker' => 'Daftar Terbaik',
            'intro' => $this->content->intro($headline, $bullets, ['name' => $facilityName, 'city' => $cityName]),
            'items' => $this->rankedItems($facilityName, $cityName),
            'faqs' => $this->content->faqs($facilityName, ['name' => $facilityName, 'city' => $cityName]),
            'seoTitle' => "{$headline} — ".cms('branding.title', config('app.name')),
            'seoDescription' => "Panduan memilih {$facilityName} terbaik di {$cityName}{$yearSuffix}, lengkap dengan kriteria layanan, fasilitas, dan sistem informasi kesehatan modern.",
            'canonical' => url()->current(),
            'schemaType' => 'ItemList',
        ]);
    }

    /** /alternatif-{competitor} */
    public function alternative(string $competitor): View
    {
        $name = SeoData::alternativeName($competitor);
        abort_if(! $name, 404);

        $app = cms('branding.title', config('app.name'));
        $headline = "Alternatif {$name} — Solusi SIMRS Modern";

        return view('pseo.alternative', [
            'headline' => $headline,
            'competitor' => $name,
            'appName' => $app,
            'intro' => $this->content->intro($headline, array_slice(array_values(SeoData::FEATURES), 0, 6), ['name' => $app]),
            'comparison' => $this->comparisonRows($name, $app),
            'faqs' => $this->content->faqs($app, ['name' => $app]),
            'seoTitle' => "Alternatif {$name} Terbaik — {$app}",
            'seoDescription' => "Cari alternatif {$name}? {$app} menawarkan rekam medis elektronik, integrasi BPJS & SatuSehat, dan source code yang bisa di-whitelabel.",
            'canonical' => url()->current(),
        ]);
    }

    /** /bandingkan/{a}-vs-{b} */
    public function compare(string $pair): View
    {
        abort_unless(str_contains($pair, '-vs-'), 404);
        [$aSlug, $bSlug] = explode('-vs-', $pair, 2);
        $a = SeoData::alternativeName($aSlug);
        $b = SeoData::alternativeName($bSlug);
        abort_if(! $a || ! $b, 404);

        $headline = "{$a} vs {$b} — Perbandingan Lengkap";

        return view('pseo.compare', [
            'headline' => $headline,
            'a' => $a,
            'b' => $b,
            'intro' => $this->content->intro($headline, [], ['name' => "{$a} dan {$b}"]),
            'rows' => $this->headToHead($a, $b),
            'faqs' => $this->content->faqs("{$a} vs {$b}", ['name' => "{$a} dan {$b}"]),
            'seoTitle' => "{$headline} — ".cms('branding.title', config('app.name')),
            'seoDescription' => "Perbandingan head-to-head antara {$a} dan {$b}: fitur, integrasi, harga, dan kemudahan implementasi untuk fasilitas kesehatan.",
            'canonical' => url()->current(),
        ]);
    }

    /** /fitur-{slug}  → slug = {feature} atau {feature}-{city} */
    public function feature(string $slug): View
    {
        [$feature, $featureName, $rest] = $this->matchPrefix($slug, SeoData::FEATURES);
        abort_if(! $featureName, 404);

        $cityName = null;
        if ($rest !== '') {
            $cityName = SeoData::cityName($rest);
            abort_if(! $cityName, 404);
        }

        $suffix = $cityName ? " untuk Faskes di {$cityName}" : '';
        $headline = "{$featureName}{$suffix}";

        return view('pseo.feature', [
            'headline' => $headline,
            'featureName' => $featureName,
            'cityName' => $cityName,
            'intro' => $this->content->intro($headline, array_slice(array_values(SeoData::FEATURES), 0, 6), ['name' => $featureName, 'city' => $cityName]),
            'relatedFeatures' => collect(SeoData::FEATURES)->take(9)->toArray(),
            'faqs' => $this->content->faqs($featureName, ['name' => $featureName, 'city' => $cityName]),
            'seoTitle' => "{$headline} — ".cms('branding.title', config('app.name')),
            'seoDescription' => "Modul {$featureName} pada sistem informasi rumah sakit modern".($cityName ? " untuk fasilitas kesehatan di {$cityName}" : '').'. Terintegrasi, efisien, dan siap pakai.',
            'canonical' => url()->current(),
        ]);
    }

    /** /aplikasi-{slug}  → slug = {facility}-{city} */
    public function facilityCity(string $slug): View
    {
        [$facility, $facilityName, $rest] = $this->matchPrefix($slug, SeoData::FACILITY_TYPES);
        $cityName = $rest !== '' ? SeoData::cityName($rest) : null;
        abort_if(! $facilityName || ! $cityName, 404);

        $headline = "Aplikasi {$facilityName} di {$cityName}";

        return view('pseo.landing', [
            'headline' => $headline,
            'intro' => $this->content->intro($headline, array_slice(array_values(SeoData::FEATURES), 0, 8), ['name' => "Aplikasi {$facilityName}", 'city' => $cityName]),
            'features' => SeoData::FEATURES,
            'faqs' => $this->content->faqs("Aplikasi {$facilityName}", ['name' => "Aplikasi {$facilityName}", 'city' => $cityName]),
            'seoTitle' => "{$headline} — ".cms('branding.title', config('app.name')),
            'seoDescription' => "Solusi aplikasi {$facilityName} di {$cityName}: rekam medis, antrian, farmasi, BPJS & SatuSehat. Source code bisa di-whitelabel.",
            'canonical' => url()->current(),
        ]);
    }

    /** /aplikasi-poli-{slug}  → slug = {specialty}-{city} */
    public function specialtyCity(string $slug): View
    {
        [$sp, $spName, $rest] = $this->matchPrefix($slug, SeoData::SPECIALTIES);
        $cityName = $rest !== '' ? SeoData::cityName($rest) : null;
        abort_if(! $spName || ! $cityName, 404);

        $headline = "Aplikasi {$spName} di {$cityName}";

        return view('pseo.landing', [
            'headline' => $headline,
            'intro' => $this->content->intro($headline, array_slice(array_values(SeoData::FEATURES), 0, 6), ['name' => "Aplikasi {$spName}", 'city' => $cityName]),
            'features' => SeoData::FEATURES,
            'faqs' => $this->content->faqs("Aplikasi {$spName}", ['name' => "Aplikasi {$spName}", 'city' => $cityName]),
            'seoTitle' => "{$headline} — ".cms('branding.title', config('app.name')),
            'seoDescription' => "Sistem informasi untuk {$spName} di {$cityName}. Kelola rekam medis, jadwal dokter, dan antrian pasien secara digital.",
            'canonical' => url()->current(),
        ]);
    }

    /**
     * Generic source-code sales handler. Matches many patterns via one method.
     * Handles: /beli-{kw}, /{kw}-{city}, /{kw}-{scale}, /{kw}-{city}-{scale}, /{kw}-fitur-{feature}
     */
    public function sourceCode(Request $request): View
    {
        $path = trim($request->path(), '/');
        $slug = Str::of($path)->after('beli-')->toString();
        $slug = $path === $slug ? $path : $slug;

        [$keyword, $cityName, $scaleName, $featureName] = $this->resolveSourceCodeSlug($slug);
        abort_if(! $keyword, 404);

        $app = cms('branding.title', config('app.name'));
        $parts = [$keyword];
        if ($featureName) {
            $parts[] = "Fitur {$featureName}";
        }
        if ($cityName) {
            $parts[] = $cityName;
        }
        if ($scaleName) {
            $parts[] = $scaleName;
        }
        $headline = implode(' — ', $parts);

        return view('pseo.source-code', [
            'headline' => $headline,
            'keyword' => $keyword,
            'cityName' => $cityName,
            'scaleName' => $scaleName,
            'featureName' => $featureName,
            'appName' => $app,
            'intro' => $this->content->intro($headline, array_slice(array_values(SeoData::FEATURES), 0, 8), ['name' => $keyword, 'city' => $cityName]),
            'features' => SeoData::FEATURES,
            'faqs' => $this->content->faqs($keyword, ['name' => $keyword, 'city' => $cityName]),
            'seoTitle' => "{$headline} — {$app}",
            'seoDescription' => Str::limit("Jual {$keyword}".($cityName ? " untuk faskes di {$cityName}" : '').'. Source code lengkap, whitelabel, integrasi BPJS & SatuSehat. Hubungi kami sekarang.', 155),
            'canonical' => url()->current(),
        ]);
    }

    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────

    /**
     * Match a slug against a map (slug => name) using longest-prefix.
     * Returns [matchedSlug, matchedName, remainder].
     */
    protected function matchPrefix(string $slug, array $map): array
    {
        $keys = array_keys($map);
        usort($keys, fn ($a, $b) => strlen($b) <=> strlen($a));

        foreach ($keys as $key) {
            if ($slug === $key) {
                return [$key, $map[$key], ''];
            }
            if (str_starts_with($slug, $key.'-')) {
                return [$key, $map[$key], substr($slug, strlen($key) + 1)];
            }
        }

        return [null, null, $slug];
    }

    protected function resolveSourceCodeSlug(string $slug): array
    {
        $keyword = $cityName = $scaleName = $featureName = null;

        // Longest keyword match first
        $keywords = SeoData::SOURCE_CODE_KEYWORDS;
        uksort($keywords, fn ($a, $b) => strlen($b) <=> strlen($a));

        foreach ($keywords as $kwSlug => $kwName) {
            if ($slug === $kwSlug || str_starts_with($slug, $kwSlug)) {
                $keyword = $kwName;
                $rest = trim(Str::after($slug, $kwSlug), '-');

                if ($rest !== '') {
                    // feature suffix: fitur-{feature}
                    if (str_starts_with($rest, 'fitur-')) {
                        $fSlug = Str::after($rest, 'fitur-');
                        $featureName = SeoData::featureName($fSlug);
                        if (! $featureName) {
                            return [null, null, null, null];
                        }
                    } else {
                        // try city, scale, or city-scale
                        foreach (SeoData::SCALES as $scSlug => $scName) {
                            if (str_ends_with($rest, $scSlug)) {
                                $scaleName = $scName;
                                $rest = trim(Str::beforeLast($rest, $scSlug), '-');
                                break;
                            }
                        }
                        if ($rest !== '') {
                            $cityName = SeoData::cityName($rest);
                            if (! $cityName) {
                                return [null, null, null, null];
                            }
                        }
                    }
                }
                break;
            }
        }

        return [$keyword, $cityName, $scaleName, $featureName];
    }

    protected function rankedItems(string $facility, string $city): array
    {
        $criteria = [
            'Rekam medis elektronik terstandar & mudah dipakai',
            'Integrasi BPJS (VClaim & Antrean) yang mulus',
            'Terhubung SatuSehat Kemenkes',
            'Manajemen farmasi, laboratorium, dan radiologi',
            'Dashboard keuangan & laporan real-time',
            'Portal pasien & antrian online',
        ];
        $items = [];
        for ($i = 1; $i <= 10; $i++) {
            $items[] = [
                'rank' => $i,
                'title' => "{$facility} Pilihan #{$i} di {$city}",
                'desc' => 'Menerapkan '.$criteria[($i - 1) % count($criteria)].' untuk meningkatkan mutu pelayanan dan efisiensi operasional.',
            ];
        }

        return $items;
    }

    protected function comparisonRows(string $competitor, string $app): array
    {
        return [
            ['aspek' => 'Rekam Medis Elektronik', 'them' => 'Tersedia', 'us' => 'Tersedia, standar Kemenkes'],
            ['aspek' => 'Integrasi BPJS & SatuSehat', 'them' => 'Terbatas', 'us' => 'Lengkap & otomatis'],
            ['aspek' => 'Source Code Disertakan', 'them' => 'Umumnya tidak', 'us' => 'Ya, full source code'],
            ['aspek' => 'Whitelabel & Self-Host', 'them' => 'Terbatas', 'us' => 'Bebas rebrand & host sendiri'],
            ['aspek' => 'Biaya Langganan', 'them' => 'Bulanan berkelanjutan', 'us' => 'Sekali beli, tanpa lock-in'],
            ['aspek' => 'Kustomisasi Modul', 'them' => 'Bergantung vendor', 'us' => 'Bebas modifikasi'],
        ];
    }

    protected function headToHead(string $a, string $b): array
    {
        return [
            ['aspek' => 'Kelengkapan Modul Klinis', $a => 'Standar', $b => 'Standar'],
            ['aspek' => 'Integrasi BPJS & SatuSehat', $a => 'Bervariasi', $b => 'Bervariasi'],
            ['aspek' => 'Kemudahan Implementasi', $a => 'Sedang', $b => 'Sedang'],
            ['aspek' => 'Dukungan Whitelabel', $a => 'Terbatas', $b => 'Terbatas'],
            ['aspek' => 'Model Harga', $a => 'Langganan', $b => 'Langganan'],
        ];
    }
}
