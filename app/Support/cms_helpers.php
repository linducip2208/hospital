<?php

use App\Models\PageContent;

if (! function_exists('cms')) {
    /**
     * Ambil field konten dari CMS PageContent.
     *
     * Contoh:
     *   cms('hero.title', 'Default Title')
     *   cms('hero.meta.cta_primary_text', 'Demo')
     *   cms('modules.meta.items', [])
     *
     * @param  string  $path  format: "section" atau "section.field" atau "section.meta.key.subkey"
     * @param  mixed   $default
     * @return mixed
     */
    function cms(string $path, mixed $default = null): mixed
    {
        $parts = explode('.', $path);
        $section = array_shift($parts);

        $record = PageContent::published()->get($section);

        if (! $record) {
            return $default;
        }

        if (empty($parts)) {
            return $record;
        }

        $field = $parts[0];

        // Direct field di tabel
        if (in_array($field, ['title', 'subtitle', 'content', 'image_url', 'button_text', 'button_url', 'video_url', 'is_active', 'order'])) {
            return $record->{$field} ?? $default;
        }

        // Akses meta nested
        if ($field === 'meta') {
            $meta = $record->meta ?? [];
            $remaining = array_slice($parts, 1);

            foreach ($remaining as $key) {
                if (is_array($meta) && array_key_exists($key, $meta)) {
                    $meta = $meta[$key];
                } else {
                    return $default;
                }
            }

            return $meta ?? $default;
        }

        return $default;
    }
}
