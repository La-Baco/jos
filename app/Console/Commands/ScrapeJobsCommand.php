<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ScrapeJobsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:scrape {--source=all : Filter spesifik sumber scraping (ID atau nama)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menjalankan web scraper Python modular untuk mengagregasi lowongan pekerjaan publik';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $source = $this->option('source') ?: 'all';
        $this->info("Menjalankan subsistem scraper JOS untuk sumber: [{$source}]...");

        $scraperPath = base_path('scraper'.DIRECTORY_SEPARATOR.'scraper.py');
        $pythonBinary = 'python';

        $process = new Process([$pythonBinary, $scraperPath, '--source', $source], base_path('scraper'), [
            'DB_HOST' => config('database.connections.mysql.host', '127.0.0.1'),
            'DB_PORT' => config('database.connections.mysql.port', 3306),
            'DB_DATABASE' => config('database.connections.mysql.database', 'jos'),
            'DB_USERNAME' => config('database.connections.mysql.username', 'root'),
            'DB_PASSWORD' => config('database.connections.mysql.password', ''),
        ]);

        $process->setTimeout(300);

        $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        if (! $process->isSuccessful()) {
            $this->error('Terjadi kesalahan saat menjalankan proses scraping Python.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Scraping berhasil diselesaikan dan data lowongan telah diperbarui.');

        return self::SUCCESS;
    }
}
