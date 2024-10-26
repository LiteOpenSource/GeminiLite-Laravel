<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Providers;

use Illuminate\Support\ServiceProvider;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiServiceInterface;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\UploadFileToGeminiServiceInterface;
use LiteOpenSource\GeminiLiteLaravel\Src\Services\GeminiService;
use LiteOpenSource\GeminiLiteLaravel\Src\Services\UploadFileToGeminiService;

class GeminiLiteServiceProvider extends ServiceProvider
{
    public function register()
    {
        // REGISTER: Merging config file to config file for Laravel APP
        //TODO: This does not work, does't merge with config files
        $this->mergeConfigFrom(
            __DIR__.'/../../config/geminilite.php', 'geminilite'
        );

        // REGISTER: UploadFileToGeminiService to service container
        $this->app->bind(UploadFileToGeminiServiceInterface::class, function ($app) {
            $geminiLiteSecretApiKey = config('geminilite.geminilite_secret_api_key');
            return new UploadFileToGeminiService($geminiLiteSecretApiKey);
        });

        // REGISTER: GeminiService to service container
        $this->app->bind(GeminiServiceInterface::class, function ($app){
            $geminiLiteSecretApiKey = config('geminilite.geminilite_secret_api_key');
            return new GeminiService($geminiLiteSecretApiKey );
        });
    }

    public function boot()
    {
        // PUBLISH: Publishing config file to config folder for Laravel App using --tag="geminilite-config"
        $this->publishes([
            __DIR__.'/../../config/geminilite.php' => config_path('geminilite.php'),
        ], 'geminilite-config');

        // PUBLISH: Migrations
        //TODO: This does not work, does't copy migratioons to migrations folder
        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations')
        ]);

        //TODO: Try if this works and it's necesary
        // PUBLISH: Comand tha run seeder comand using "php artisan geminilite:seed"
        if ($this->app->runningInConsole()) {
            $this->commands([
                \LiteOpenSource\GeminiLiteLaravel\Src\Commands\RunSeederCommand::class,
            ]);
        }
    }
}

