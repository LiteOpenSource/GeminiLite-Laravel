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
    //Guzzle client
    private $guzzleClient;
    private $secretAPIKey;

    //---------------------------- CONSTRUCTOR SECTION --------------------------
    //---------------------------- CONSTRUCTOR SECTION --------------------------
    public function __construct($secretAPIKey){
        Log::info("[ IN GeminiService ->  __construct: ]. SecretAPIKey: ". $secretAPIKey);

        $this->guzzleClient = new Client();
        $this->secretAPIKey = $secretAPIKey;
    }

    //---------------------- INTERFACE FUNCTIONS SECTION -----------------------
    //---------------------- INTERFACE FUNCTIONS SECTION -----------------------
    // TODO: I should return a interface object of GeniniChat class instead mixed type


    // TODO: Maybe I should implement two diferent methods for each type of model (GeminiFlash and GeminiPro)
    // TODO: I have 2 options: The first option is chance config model in time of execution and chose the model.
    // TODO: The second option is create 2 diferents methodes for each model and call them by the model type. Like a
    // TODO: New GeminiFlashChat and New GeminiProChat. But I think the first option is more efficient because it's can keep
    // TODO: the histori chat even though the model type change.

    public function newChat(): mixed
    {
        Log::info("[ IN GeminiService -> newChat() ] - Creating a new GeminiChat instance.");

        return new GeminiChat($this->guzzleClient, $this->secretAPIKey);
    }

    //------------------------ OTHER FUNCTIONS SECTION -------------------------
    //------------------------ OTHER FUNCTIONS SECTION -------------------------

}
