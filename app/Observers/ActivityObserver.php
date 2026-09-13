<?php

namespace App\Observers;

use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Model;

class ActivityObserver
{
    public function created(Model $model): void
    {
        ActivityLogger::log('created', $model);
    }

    public function updated(Model $model): void
    {
        $changed = collect($model->getChanges())
            ->except(['updated_at', 'remember_token', 'password'])
            ->keys()->all();

        if (empty($changed)) {
            return;
        }

        $sensitive = ['password', 'remember_token', 'token', 'secret', 'api_key'];
        $before = collect($model->getOriginal())->except($sensitive)->only($changed)->all();
        $after = collect($model->getAttributes())->except($sensitive)->only($changed)->all();
        ActivityLogger::log('updated', $model, null, ['changed' => $changed, 'before' => $before, 'after' => $after]);
    }

    public function deleted(Model $model): void
    {
        ActivityLogger::log('deleted', $model);
    }
}
