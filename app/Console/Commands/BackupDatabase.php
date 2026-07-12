<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup {--keep=14 : Jumlah file backup yang disimpan}';

    protected $description = 'Backup database MySQL ke storage (mysqldump)';

    public function handle(): int
    {
        $disk = Storage::disk('local');
        $dir = 'backups';
        $disk->makeDirectory($dir);

        $filename = 'backup-'.config('database.connections.mysql.database').'-'.now()->format('Y-m-d_His').'.sql';
        $absPath = $disk->path("{$dir}/{$filename}");

        $db = config('database.connections.mysql');
        $host = $db['host'];
        $port = $db['port'] ?? 3306;
        $user = $db['username'];
        $pass = $db['password'];
        $name = $db['database'];

        $passPart = $pass !== '' && $pass !== null ? '--password='.escapeshellarg($pass).' ' : '';
        $cmd = sprintf(
            'mysqldump --host=%s --port=%s --user=%s %s--single-transaction --routines --triggers %s > %s',
            escapeshellarg($host),
            escapeshellarg((string) $port),
            escapeshellarg($user),
            $passPart,
            escapeshellarg($name),
            escapeshellarg($absPath)
        );

        $this->info('Menjalankan backup database...');
        exec($cmd.' 2>&1', $output, $exitCode);

        if ($exitCode !== 0) {
            $this->error('Backup gagal: '.implode("\n", $output));

            return self::FAILURE;
        }

        $sizeMb = round(filesize($absPath) / 1048576, 2);
        $this->info("Backup berhasil: {$dir}/{$filename} ({$sizeMb} MB)");

        $this->pruneOld($disk, $dir, (int) $this->option('keep'));

        return self::SUCCESS;
    }

    protected function pruneOld($disk, string $dir, int $keep): void
    {
        $files = collect($disk->files($dir))
            ->filter(fn ($f) => str_ends_with($f, '.sql'))
            ->sortByDesc(fn ($f) => $disk->lastModified($f))
            ->values();

        foreach ($files->slice($keep) as $old) {
            $disk->delete($old);
            $this->line("Hapus backup lama: {$old}");
        }
    }
}
