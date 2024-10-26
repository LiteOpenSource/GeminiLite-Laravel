<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Facades;

use Illuminate\Support\Facades\Facade;

class Gemini extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiServiceInterface';
    }
}

