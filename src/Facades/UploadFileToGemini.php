<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Facades;

use Illuminate\Support\Facades\Facade;

class UploadFileToGemini extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LiteOpenSource\GeminiLiteLaravel\Src\Contracts\UploadFileToGeminiServiceInterface';
    }
}

