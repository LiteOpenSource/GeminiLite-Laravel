<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiTokenCountInterface;

class GeminiTokenCountService implements GeminiTokenCountInterface{
    private $guzzleClient;
    private $secretAPIKey;

    public function __construct($secretAPIKey){

        $this->guzzleClient = new Client();
        $this->secretAPIKey = $secretAPIKey;
    }

    /**
     * Method to count the number of tokens in a text
     * @param $content : string
     * @return mixed
     */
    public function coutTextTokens($content):mixed  {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:countTokens?key='.$this->secretAPIKey;
         // Configura el cuerpo de la solicitud
        $body = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $content]
                    ]
                ]
            ]
        ];

        try {
            // Realiza la solicitud POST con Guzzle
            $response = $this->guzzleClient->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $body, // Guzzle se encarga de codificar el cuerpo como JSON
            ]);

            Log::info(json_decode($response->getBody(), true));
            // Devuelve la respuesta decodificada como un array o objeto
            return json_decode($response->getBody(), true)['totalTokens'] ?? null;
        } catch (\Exception $e) {
            // Maneja errores y excepciones
            return [
                'error' => $e->getMessage()
            ];
        }
        return 0;
    }

    public function countTokensWithImage(string $text, string $imagePath): mixed {
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:countTokens?key=' . $this->secretAPIKey;

        $imageData = base64_encode(file_get_contents($imagePath));

        $body = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $text],
                        [
                            'inline_data' => [
                                'mime_type' => 'image/jpeg',
                                'data' => $imageData
                            ]
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = $this->guzzleClient->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $body, 
            ]);

            return json_decode($response->getBody(), true)['totalTokens'] ?? null;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }


}