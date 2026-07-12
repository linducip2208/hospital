<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Builder::macro('whereAny', function (array $columns, string $operator, mixed $value) {
            return $this->where(function (Builder $query) use ($columns, $operator, $value) {
                foreach ($columns as $column) {
                    $query->orWhere($column, $operator, $value);
                }
            });
        });

        // Force HTTPS di production (atau ketika FORCE_HTTPS=true di .env)
        if ($this->app->environment('production') || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
    }
}
