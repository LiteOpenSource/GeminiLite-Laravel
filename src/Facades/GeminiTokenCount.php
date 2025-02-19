<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Facades;

use Illuminate\Support\Facades\Facade;

class GeminiTokenCount extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiTokenCountInterface';
    }
}