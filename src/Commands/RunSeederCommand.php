<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunSeederCommand extends Command
{
    protected $signature = 'geminilite:seed';
    protected $description = 'Run the seeder for geminilite data';

    public function handle()
    {
        $this->info('Running GeminiLiteDataSeeder...');
        Artisan::call('db:seed', ['--class' => 'LiteOpenSource\GeminiLiteLaravel\Src\Database\Seeders\GeminiLiteDataSeeder']);
        $this->info('Seeding completed.');
    }
}
