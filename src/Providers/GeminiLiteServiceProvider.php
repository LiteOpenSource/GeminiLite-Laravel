<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Providers;

use Illuminate\Support\ServiceProvider;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiServiceInterface;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiTokenCountInterface;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\UploadFileToGeminiServiceInterface;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\EmbeddingServiceInterface;
use LiteOpenSource\GeminiLiteLaravel\Src\Services\GeminiService;
use LiteOpenSource\GeminiLiteLaravel\Src\Services\GeminiTokenCountService;
use LiteOpenSource\GeminiLiteLaravel\Src\Services\UploadFileToGeminiService;
use LiteOpenSource\GeminiLiteLaravel\Src\Services\EmbeddingService;

class GeminiLiteServiceProvider extends ServiceProvider
{
    public function register()
    {
        // REGISTER: Merging config file to config file for Laravel APP
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
            return new GeminiService($geminiLiteSecretApiKey);
        });

        // REGISTER: GeminiTokeCountService to service container
        $this->app->bind(GeminiTokenCountInterface::class, function ($app){
            $geminiLiteSecretApiKey = config('geminilite.geminilite_secret_api_key');
            return new GeminiTokenCountService($geminiLiteSecretApiKey);
        });

        // REGISTER: EmbeddingService to service container
        $this->app->bind(EmbeddingServiceInterface::class, function ($app) {
            $geminiLiteSecretApiKey = config('geminilite.geminilite_secret_api_key');
            return new EmbeddingService($geminiLiteSecretApiKey);
        });
    }

    public function boot()
    {
        // PUBLISH: Publishing config file to config folder for Laravel App using --tag="geminilite-config"
        $this->publishes([
            __DIR__.'/../../config/geminilite.php' => config_path('geminilite.php'),
        ], 'geminilite-config');

        // PUBLISH: Publishing seeder file to seeder folder --tag="geminilite-config"
        $this->publishes([
            __DIR__.'/../Database/Seeders/GeminiLiteLimitTokensRulesSeeder.php' => database_path('seeders/GeminiLiteAuthDataSeeder.php'),
        ], 'geminilite-limit-tokes');

        // PUBLISH: Migrations
        $this->publishesMigrations([
            __DIR__ . '/../Database/Migrations' => database_path('migrations')
        ]);
    }
}
