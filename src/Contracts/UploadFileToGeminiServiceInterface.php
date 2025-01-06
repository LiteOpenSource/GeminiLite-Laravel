<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Contracts;

interface UploadFileToGeminiServiceInterface
{
    public function processFileFromUpload($file): mixed;
    public function processFileFromPath(string $filePath): mixed;
}
