<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'section', 'title', 'subtitle', 'content', 'meta',
        'image_url', 'button_text', 'button_url', 'video_url',
        'is_active', 'order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
            'meta' => 'array',
        ];
    }

    /**
     * Ambil semua section aktif sebagai keyed collection (cached 1 jam).
     * Cache disimpan sebagai array supaya aman dari issue Eloquent serialization.
     */
    public static function published(): \Illuminate\Support\Collection
    {
        $cached = Cache::remember('cms.published', now()->addHour(), function () {
            return static::where('is_active', true)
                ->orderBy('order')
                ->get()
                ->map(fn ($r) => $r->only(['id', 'section', 'title', 'subtitle', 'content', 'meta', 'image_url', 'button_text', 'button_url', 'video_url', 'is_active', 'order']))
                ->toArray();
        });

        // Re-hydrate jadi keyed Collection of stdClass-like (akses ->title via property)
        return collect($cached)->map(fn ($r) => (object) $r)->keyBy('section');
    }

    public static function flushCache(): void
    {
        Cache::forget('cms.published');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::flushCache());
        static::deleted(fn () => static::flushCache());
    }
}
