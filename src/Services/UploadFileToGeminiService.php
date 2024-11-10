<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Services;

use GuzzleHttp\Client;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\UploadFileToGeminiServiceInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Exception;
use Illuminate\Support\Facades\Log;
use LiteOpenSource\GeminiLiteLaravel\Src\DataObjects\UploadFileToGeminiResult;

class UploadFileToGeminiService implements UploadFileToGeminiServiceInterface
{
    protected $fileMimeType;
    protected $fileUri;
    private $secretAPIKey;

    public function __construct($secretAPIKey){
        Log::info("[ IN UploadFileToGeminiService ->  __construct: ]. SecretAPIKey: ". $secretAPIKey);

        $this->secretAPIKey = $secretAPIKey ;
        $this->fileMimeType = null;
    }

    //TODO: DONNT WORK FOR THE MOMENT, WE HAVE TO NEED IF I ADD LIOVEWIRE TO USER FILES OR I ANLY TO WOR WITH PATHS
    public function processFileFromFile($file): mixed
    {
        return $this->getURIFromFile($file);
    }


    public function processFileFromPath(string $filePath): UploadFileToGeminiResult
    {
        $this->getURIFromPath($filePath);

        if ($this->fileUri === null || $this->fileMimeType === null) {
            throw new \RuntimeException('File upload failed or resulted in null values');
        }
        return new UploadFileToGeminiResult( $this->fileUri, $this->fileMimeType);
    }

    // ---------------------------------------------------------------
    // ----------------- GETTERS AND SETTERS SECTION -----------------
    // ---------------------------------------------------------------

    public function getURIFromPath(string $filePath)
    {
        // return $this->getURI(new \Illuminate\Http\UploadedFile($path, basename($path)));

        // Instance of Guzzle client and RESP API URL
        $client = new Client();
        $url = 'https://generativelanguage.googleapis.com/upload/v1beta/files?key=';

        //Load and Process document
        try{
            Log::info("[ IN UploadFileToGeminiService -> getURIFromPath]  file: ". $filePath);


            if (!file_exists($filePath)) {
                throw new \InvalidArgumentException('File does not exist');
            }

            $filePath = realpath($filePath);
            $fileName = basename($filePath);
            $fileSize = filesize($filePath);
            $this->fileMimeType = mime_content_type($filePath);

            Log::info("[ IN UploadFileToGeminiService -> getURIFromPath]  FileRealPath: ". $filePath." - File name: ". $fileName. " - File size: ". $fileSize. " - File MimeType: ". $this->fileMimeType. " - GeminiApiKey: ". $this->secretAPIKey);


        }catch(Exception $e){

            Log::error("SYSTEM THREW:: catch Exception in ProfessionalData.php: " . $e->getMessage());
            //dd( "ERROR: Failed to load image" );
            return null;

        }

        //Getting and returning the URI of the file processed
        try{
            $response = $client->post($url . $this->secretAPIKey, [
                'headers' => [
                    'X-Goog-Upload-Command' => 'start, upload, finalize',
                    'X-Goog-Upload-Header-Content-Length' => $fileSize,
                    'X-Goog-Upload-Header-Content-Type' => $this->fileMimeType,
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'filename' => $fileName,
                        'Mime-Type' => $this->fileMimeType,
                        'contents' => fopen($filePath, 'r'),
                    ],
                ],
            ]);

            $fileUri = json_decode($response->getBody()->getContents())->file->uri;

            $this->fileUri = $fileUri;
            return;

        }catch(ConnectException $e){
            Log::error("SYSTEM THREW:: catch ConnectException in ProfessionalData.php: " . $e->getMessage());
            //dd( "Connection Failed. Try more latter");
            return null;

        }catch(RequestException $e ){
            Log::error("SYSTEM THREW:: catch RequestException in ProfessionalData.php: " . $e->getResponse()->getBody());
            //dd( "UPS! Something went wrong | ERROR CODE: " . $e->getResponse()->getStatusCode()) ;
            return null;

        }catch(Exception $e ){
            Log::error(" SYSTEM THREW:: catch Exception in ProfessionalData.php: " . $e->getMessage());
            //dd( "UPS! Something went wrong");
            return null;
        }
    }

    //TODO: DONNT WORK FOR THE MOMENT, WE HAVE TO NEED IF I ADD LIOVEWIRE TO USER FILES OR I ANLY TO WOR WITH PATHS
    public function getURIFromFile($file): mixed
    {
        // Instance of Guzzle client and RESP API URL
        $client = new Client();
        $url = 'https://generativelanguage.googleapis.com/upload/v1beta/files?key=';

        //Load and Process document
        try{

            if (!$file instanceof \Illuminate\Http\UploadedFile) {
                throw new \InvalidArgumentException('Invalid file object');
            }

            $filePath = $file->getRealPath();
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $this->fileMimeType = $file->getMimeType();


        }catch(Exception $e){

            Log::error("SYSTEM THREW:: catch Exception in ProfessionalData.php: " . $e->getMessage());
            //dd( "ERROR: Failed to load image" );
            return null;

        }

        //Getting and returning the URI of the file processed
        try{
            $response = $client->post($url . $this->secretAPIKey, [
                'headers' => [
                    'X-Goog-Upload-Command' => 'start, upload, finalize',
                    'X-Goog-Upload-Header-Content-Length' => $fileSize,
                    'X-Goog-Upload-Header-Content-Type' => $this->fileMimeType,
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'filename' => $fileName,
                        'Mime-Type' => $this->fileMimeType,
                        'contents' => fopen($filePath, 'r'),
                    ],
                ],
            ]);

            $fileUri = json_decode($response->getBody()->getContents())->file->uri;

            return $fileUri;

        }catch(ConnectException $e){
            Log::error("SYSTEM THREW:: catch ConnectException in ProfessionalData.php: " . $e->getMessage());
            //dd( "Connection Failed. Try more latter");
            return null;

        }catch(RequestException $e ){
            Log::error("SYSTEM THREW:: catch RequestException in ProfessionalData.php: " . $e->getResponse()->getBody());
            //dd( "UPS! Something went wrong | ERROR CODE: " . $e->getResponse()->getStatusCode()) ;
            return null;

        }catch(Exception $e ){
            Log::error(" SYSTEM THREW:: catch Exception in ProfessionalData.php: " . $e->getMessage());
            //dd( "UPS! Something went wrong");
            return null;
        }
    }

}
