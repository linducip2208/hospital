<?php

namespace App\Console\Commands;

use App\Services\Seo\IndexNowService;
use App\Services\Seo\SitemapBuilder;
use Illuminate\Console\Command;

class IndexNowSubmit extends Command
{
    protected $signature = 'seo:indexnow
                            {--all : Submit semua URL sitemap}
                            {--new : Submit hanya URL baru sejak run terakhir}
                            {--url= : Submit satu URL}';

    protected $description = 'Submit URL ke IndexNow (Bing, Yandex, Seznam, Naver)';

    public function handle(IndexNowService $service): int
    {
        if ($url = $this->option('url')) {
            $result = $service->submitSingle($url);
            $this->info('Submitted 1 URL. Success: '.(! empty($result['success']) ? 'yes' : 'no'));

            return self::SUCCESS;
        }

        if ($this->option('new')) {
            $builder = new SitemapBuilder;
            $urls = [];
            foreach ($builder->index() as $group) {
                foreach ($builder->urlsForGroup($group) as $u) {
                    $urls[] = $u['loc'];
                }
            }
            $result = $service->submitNewOnly($urls);
            $this->info("New URLs submitted: {$result['submitted']}");

            return self::SUCCESS;
        }

        $this->info('Submitting all PSEO URLs to IndexNow...');
        $result = $service->submitAll();
        if (! empty($result['success'])) {
            $this->info("Successfully submitted {$result['submitted']} URLs.");
        } else {
            $this->error('Submission failed: '.($result['message'] ?? 'unknown'));
        }

        return self::SUCCESS;
    }
}
