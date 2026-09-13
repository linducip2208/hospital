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
        $temporaryPath = $absPath.'.tmp';

        $db = config('database.connections.mysql');
        $host = $db['host'];
        $port = $db['port'] ?? 3306;
        $user = $db['username'];
        $pass = $db['password'];
        $name = $db['database'];

        $cmd = implode(' ', [
            escapeshellarg('mysqldump'),
            escapeshellarg('--host='.$host),
            escapeshellarg('--port='.$port),
            escapeshellarg('--user='.$user),
            escapeshellarg('--single-transaction'),
            escapeshellarg('--routines'),
            escapeshellarg('--triggers'),
            escapeshellarg('--result-file='.$temporaryPath),
            escapeshellarg($name),
        ]);

        $this->info('Menjalankan backup database...');
        $previousPassword = getenv('MYSQL_PWD');
        putenv('MYSQL_PWD='.((string) $pass));
        $process = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, base_path());

        if (! is_resource($process)) {
            putenv($previousPassword === false ? 'MYSQL_PWD' : 'MYSQL_PWD='.$previousPassword);
            $this->error('Backup gagal: proses mysqldump tidak dapat dijalankan.');

            return self::FAILURE;
        }

        $output = trim(stream_get_contents($pipes[1])."\n".stream_get_contents($pipes[2]));
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);
        putenv($previousPassword === false ? 'MYSQL_PWD' : 'MYSQL_PWD='.$previousPassword);

        if ($exitCode !== 0) {
            @unlink($temporaryPath);
            $this->error('Backup gagal'.($output !== '' ? ': '.$output : '.'));

            return self::FAILURE;
        }

        if (! is_file($temporaryPath) || filesize($temporaryPath) === 0 || ! @rename($temporaryPath, $absPath)) {
            @unlink($temporaryPath);
            $this->error('Backup gagal: file hasil backup kosong atau tidak dapat disimpan.');

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
