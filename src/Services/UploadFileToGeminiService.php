<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Services;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\UploadFileToGeminiServiceInterface;
use Illuminate\Support\Facades\Log;
use LiteOpenSource\GeminiLiteLaravel\Src\DataObjects\UploadFileToGeminiResult;
use LiteOpenSource\GeminiLiteLaravel\Src\Traits\UploadFileToGeminiService\CanGetUploadFileGeminiServicePrivateFunctions;

class UploadFileToGeminiService implements UploadFileToGeminiServiceInterface
{
    //---------------------------- PROPERTIES TRAITS --------------------------
    //---------------------------- PROPERTIES TRAITS --------------------------
    use CanGetUploadFileGeminiServicePrivateFunctions;

    //---------------------------- PROPERTIES SECTION --------------------------
    //---------------------------- PROPERTIES SECTION --------------------------
    protected $fileMimeType;
    protected $fileUri;
    private $secretAPIKey;

    public function __construct($secretAPIKey){
        Log::info("[ IN UploadFileToGeminiService ->  __construct: ]. SecretAPIKey: ". $secretAPIKey);

        $this->secretAPIKey = $secretAPIKey ;
        $this->fileMimeType = null;
    }

    public function processFileFromUpload($file): mixed
    {
        $filePath = $file->getRealPath();
        $this->getURIAndMimeType($filePath);

        if ($this->fileUri === null || $this->fileMimeType === null) {
            throw new \RuntimeException('File upload failed or resulted in null values');
        }
        return new UploadFileToGeminiResult( $this->fileUri, $this->fileMimeType);
    }

    public function processFileFromPath(string $filePath): UploadFileToGeminiResult
    {
        $this->getURIAndMimeType($filePath);

        if ($this->fileUri === null || $this->fileMimeType === null) {
            throw new \RuntimeException('File upload failed or resulted in null values');
        }
        return new UploadFileToGeminiResult( $this->fileUri, $this->fileMimeType);
    }
}
