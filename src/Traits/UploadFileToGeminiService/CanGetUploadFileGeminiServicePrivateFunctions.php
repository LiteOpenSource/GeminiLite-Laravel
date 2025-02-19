<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Traits\UploadFileToGeminiService;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Exception;
use Illuminate\Support\Facades\Log;


trait CanGetUploadFileGeminiServicePrivateFunctions
{
    private function getURIAndMimeType(string $filePath)
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

}
