<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Contracts;

interface UploadFileToGeminiServiceInterface
{
    public function processFileFromFile($file): mixed;
    public function processFileFromPath(string $filePath): mixed;
}
