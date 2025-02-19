<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array embedText(string $text, array $options = [])
 * @method static array embedBatch(array $texts, array $options = [])
 */
class Embedding extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LiteOpenSource\GeminiLiteLaravel\Src\Contracts\EmbeddingServiceInterface';
    }
}