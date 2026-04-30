<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Builder::macro('whereAny', function (array $columns, string $operator, mixed $value) {
            return $this->where(function (Builder $query) use ($columns, $operator, $value) {
                foreach ($columns as $column) {
                    $query->orWhere($column, $operator, $value);
                }
            });
        });
    }
}
