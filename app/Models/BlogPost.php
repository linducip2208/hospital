<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPost extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'featured_image',
        'category_id', 'author_id', 'published_at', 'is_published',
        'meta_title', 'meta_description', 'views',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    public function scopeByCategory(Builder $query, string $slug): Builder
    {
        return $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
