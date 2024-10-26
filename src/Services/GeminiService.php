<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Services;

use GuzzleHttp\Client;
//TODO: Che if I need to add Exceptions and Logs
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use LiteOpenSource\GeminiLiteLaravel\Src\Clases\GeminiChat;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiServiceInterface;
use LiteOpenSource\GeminiLiteLaravel\Src\Traits\GeminiConfigAndPropertiesJSONStructures;


class GeminiService implements GeminiServiceInterface
{

    /*
        --------------------------------------------------------------------------
        --------------------------- PREPERTIES SECTION ---------------------------
        --------------------------------------------------------------------------
    */
    //TODO: Change coment documentatio to JSON megminiModelConfig
    //JSON structures to assemblig request into trait
    use GeminiConfigAndPropertiesJSONStructures;
    //Guzzle client
    private $guzzleClient;

    private $geminiChatInstaces;

    //---------------------------- CONSTRUCTOR SECTION --------------------------
    //---------------------------- CONSTRUCTOR SECTION --------------------------
    public function __construct($secretAPIKey){
        Log::info("[ IN GeminiService ->  __construct: ]. SecretAPIKey: ". $secretAPIKey);

        $this->guzzleClient = new Client();
        $this->initDefaultConfigGeminiAPIJSON($secretAPIKey);
        $this->geminiChatInstaces = new Collection();
    }

    //---------------------- INTERFACE FUNCTIONS SECTION -----------------------
    //---------------------- INTERFACE FUNCTIONS SECTION -----------------------
    //TODO: I should return a interface object of GeniniChat class instead mixed type
    public function newChat(): mixed
    {
        Log::info("[ IN GeminiService ->  gemini(): ]. ");

        $geminiModelConfig = $this->getGeminiModelConfig();
        $geminiChatInstace = new GeminiChat($geminiModelConfig, $this->guzzleClient, $this->urlAPItoGeminiFlash);
        $this->geminiChatInstaces->push($geminiChatInstace);
        return $geminiChatInstace;
    }

    //TODO: I should return a array instead mixed type
    //TODO: Verify if I need to add control error management when request getGeminiModelConfig and this is the first funciotn caller. Maybe this function can return error null parameters.
    //TODO: Thiking about before TODO, I need to verify urlAPI and secretAPIKey.

    public function getGeminiModelConfig(): mixed
    {
        return $this->modelConfigJSON;
    }

    public function setGeminiModelConfig($temperature, $topK, $topP, $maxOutputTokens, $responseMimeType, $geminiChatinstance)
    {
        $this->modelConfigJSON['temperature'] = $temperature;
        $this->modelConfigJSON['topK'] = $topK;
        $this->modelConfigJSON['topP'] = $topP;
        $this->modelConfigJSON['maxOutputTokens'] = $maxOutputTokens;
        $this->modelConfigJSON['responseMimeType'] = $responseMimeType;
        // TODO: Verify if I need to add urlAPI to change between models
        // TODO: Since delete urlAPI to modelCongidJSON structure, I will send the urlAPI in updateConfig function to changeConfig medel, Like a do in geminiChat constructor

        if ($this->geminiChatInstaces->contains($geminiChatinstance)) {
            $geminiChatinstance->updateConfig($this->modelConfigJSON);
        }
    }

    //------------------------ OTHER FUNCTIONS SECTION -------------------------
    //------------------------ OTHER FUNCTIONS SECTION -------------------------
    //TODO: Verify if I need to init default config here, because I have the same in the JSON Object in the Trait

    private function initDefaultConfigGeminiAPIJSON($secretAPIKey)
    {
        $this->modelConfigJSON['temperature'] = 1;
        $this->modelConfigJSON['topK'] = 64;
        $this->modelConfigJSON['topP'] = 0.95;
        $this->modelConfigJSON['maxOutputTokens'] = 8192;
        $this->modelConfigJSON['responseMimeType'] = "text/plain";
        //TODO: I think that is correct config secret apiKey here, but... I can to do this in the trait
        $this->urlAPItoGeminiFlash = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $secretAPIKey;
    }

}
