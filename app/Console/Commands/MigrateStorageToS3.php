<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateStorageToS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:migrate-to-s3 {--visibility=public : Visibility for uploaded objects (public|private)} {--path=projects : Path inside storage/app/public to migrate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy files from local storage (storage/app/public) to the configured object-storage disk, such as s3 or r2.';

    public function handle()
    {
        $this->info('Starting storage migration...');

        $disk = config('filesystems.default', 'public');
        if (! in_array($disk, ['s3', 'r2'], true)) {
            $this->warn("Configured filesystem disk is '$disk'. This command is intended to be used with an S3-compatible object disk such as s3 or r2.");
            if (! $this->confirm('Continue anyway?', false)) {
                $this->info('Aborted.');
                return 1;
            }
        }

        $relativeBase = trim($this->option('path'), '/');
        $localBase = storage_path('app/public/' . $relativeBase);
        if (! is_dir($localBase)) {
            $this->error("Local path does not exist: $localBase");
            return 1;
        }

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($localBase, \RecursiveDirectoryIterator::SKIP_DOTS));
        $total = 0;
        foreach ($files as $file) {
            if ($file->isFile()) $total++;
        }

        $this->info("Found $total files under storage/app/public/{$relativeBase}");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $s3 = Storage::disk($disk);

        foreach ($files as $file) {
            if (! $file->isFile()) continue;

            $path = $file->getPathname();
            $relative = ltrim(str_replace(storage_path('app/public'), '', $path), DIRECTORY_SEPARATOR);

            try {
                $stream = fopen($path, 'r');
                if ($stream === false) {
                    $this->error("\nFailed to open file: $path");
                    continue;
                }

                // Put using stream to avoid memory issues
                $options = [];
                $visibility = $this->option('visibility');
                if (! empty($visibility)) {
                    $options['visibility'] = $visibility;
                }

                $s3->put($relative, $stream, $options);
                if (is_resource($stream)) fclose($stream);
            } catch (\Exception $e) {
                $this->error("\nFailed to upload $relative: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Migration complete. Verify files in your S3 bucket.');

        return 0;
    }
}
