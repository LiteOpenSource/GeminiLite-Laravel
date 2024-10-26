<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\DataObjects;

class UploadFileToGeminiResult
{
    private string $uri;
    private string $mimeType;

    public function __construct(string $uri, string $mimeType)
    {
        $this->uri = $uri;
        $this->mimeType = $mimeType;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }
}
