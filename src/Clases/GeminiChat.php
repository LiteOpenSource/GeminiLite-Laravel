<?php
namespace LiteOpenSource\GeminiLiteLaravel\Src\Clases;

use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiChatInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Exception;
use Illuminate\Support\Facades\Log;
use LiteOpenSource\GeminiLiteLaravel\Src\Traits\GeminiRequestAndResponsesJSONStructures;

class GeminiChat implements GeminiChatInterface
{

    //---------------------------- PROPERTIES SECTION --------------------------
    //---------------------------- PROPERTIES SECTION --------------------------
    private $geminiModelConfig;
    private $guzzleClient;
    use GeminiRequestAndResponsesJSONStructures;

    // TODO: Verify if I need to send this properties to the trait
    // Token information about chat
    // --> "promptTokenCount" represent the number of token in last prompt
    // --> "candidatesTokenCount" represent the number of tokens returned to last respose
    // --> "totalTokenCount" represent the number of total tokens
    //      between input and output

    // TODO: - - - IMPORTANT --> I have to do private these properties <-- IMPORTANT - - -
    // TODO: - - - IMPORTANT --> I have to do private these properties <-- IMPORTANT - - -
    // TODO: - - - IMPORTANT --> I have to do private these properties <-- IMPORTANT - - -
    public $promptTokenCount;
    public $candidatesTokenCount;
    public $totalTokenCount;
    public $totalTokenHistoryChatCount;
    private $urlAPIModel;


    //---------------------------- CONSTRUCTOR SECTION --------------------------
    //---------------------------- CONSTRUCTOR SECTION --------------------------
    public function __construct($geminiModelConfig, $guzzleClient, $urlAPIModel)
    {
        $this->initGlobalProperties();
        $this->geminiModelConfig = $geminiModelConfig;
        $this->guzzleClient = $guzzleClient;
        $this->urlAPIModel = $urlAPIModel;
    }

    //---------------------- INTERFACE FUNCTIONS SECTION -----------------------
    //---------------------- INTERFACE FUNCTIONS SECTION -----------------------
    // TODO: Add function in future

    public function getHistory(): mixed
    {
        return true;
    }

    public function newPrompt($textPrompt, $fileURI = null, $mimeTipe = null): mixed
    {
        Log::info("[ IN GeminiChat ->  newPrompt: ]. Gemini current model config: ", [$this->geminiModelConfig, $this->urlAPIModel]);

        // Assembling JSON File Request
        ($fileURI && $mimeTipe)
            ? $this->assemblingJSONFileRequest($textPrompt, $fileURI, $mimeTipe)
            : $this->assemblingJSONTextRequest($textPrompt);

        try {

            //Make api gemini request and return response
            if($fileURI && $mimeTipe){
                return $this->makeFileRequestToGeminiAPI();
            } else {
                return $this->makeTextRequestToGeminiAPI();
            }

        } catch (ConnectException $e) {
            Log::error("SYSTEM THREW:: catch ConnectException in GeminiAPI.php: " . $e->getMessage());
            dd( "Connection Failed. Try more latter");
            return null;

        } catch (RequestException $e) {
            Log::error("SYSTEM THREW:: catch RequestException in GeminiAPI.php: " . $e->getResponse()->getBody());
            dd( "UPS! Something went wrong | ERROR CODE: " . $e->getResponse()->getStatusCode()) ;
            return null;

        } catch (Exception $e) {
            Log::error(" SYSTEM THREW:: catch Exception in GeminiAPI.php: " . $e->getMessage());
            dd( "UPS! Something went wrong.");
            return null;
        }
    }

    //------------------------ OTHER FUNCTIONS SECTION -------------------------
    //------------------------ OTHER FUNCTIONS SECTION -------------------------
    private function initGlobalProperties()
    {
        $this->promptTokenCount = 0;
        $this->candidatesTokenCount = 0;
        $this->totalTokenCount = 0;
        $this->totalTokenHistoryChatCount = 0;
    }

    public function assemblingJSONFileRequest($textPrompt, $fileURI, $mimeTipe){
        // Assembling file message
        $this->newFileMessageJSON['role'] = "user";
        $this->newFileMessageJSON['parts'][0]['fileData']['fileUri'] = $fileURI;
        $this->newFileMessageJSON['parts'][0]['fileData']['mimeType'] = $mimeTipe;

        array_push($this->chatHistoryJSON, $this->newFileMessageJSON);

        // Assembling text prompt after file message
        $this->newTextMessageJSON['parts'][0]['text'] = $textPrompt;
        $this->newTextMessageJSON['role'] = "user";

        array_push($this->chatHistoryJSON, $this->newTextMessageJSON);

        // Assembling body of request
        $this->bodyJSON['contents'] = $this->chatHistoryJSON;
        $this->bodyJSON['generationConfig'] = $this->geminiModelConfig;
    }



    public function assemblingJSONTextRequest($textPrompt){
        Log::info("[ IN GeminiChat ->  assemblingJSONTextRequest: ]. textPrompt received: " , [$textPrompt]);


        // Assembling text prompt
        $this->newTextMessageJSON['parts'][0]['text'] = $textPrompt;
        $this->newTextMessageJSON['role'] = "user";

        array_push($this->chatHistoryJSON, $this->newTextMessageJSON);
        Log::info("[ IN GeminiChat ->  assemblingJSONTextRequest: ]. newTextMessageJSON: " , [$this->newTextMessageJSON]);
        Log::info("[ IN GeminiChat ->  assemblingJSONTextRequest: ]. chatHistoryJSON: " , [$this->chatHistoryJSON]);



        // Assembling body of request
        $this->bodyJSON['contents'] = $this->chatHistoryJSON;
        $this->bodyJSON['generationConfig'] = $this->geminiModelConfig;
        Log::info("[ IN GeminiChat ->  assemblingJSONTextRequest: ]. bodyJSON: " , [$this->bodyJSON]);

    }

    public function makeTextRequestToGeminiAPI(){
        // Make Request to Google Gemini REST API and get data from body
        Log::info("[ IN GeminiChat ->  makeTextRequestToGeminiAPI: ]. headersJSON" , [$this->headersJSON]);
        Log::info("[ IN GeminiChat ->  makeTextRequestToGeminiAPI: ]. geminiModelConfig" , [$this->geminiModelConfig]);
        Log::info("[ IN GeminiChat ->  makeTextRequestToGeminiAPI: ]. urlAPIModel" , [$this->urlAPIModel]);


        $response = $this->guzzleClient->request('POST', $this->urlAPIModel, [
            'headers' => $this->headersJSON,
            'json' => $this->bodyJSON
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);

        // Get text message, update chat history and return response
        $responseTextMessage = $responseData['candidates'][0]['content']['parts'][0]['text'];

        $this->addResponseToChatHistory($responseTextMessage, $responseData);

        return $responseTextMessage;
    }

    public function makeFileRequestToGeminiAPI(){
        // Make Request to Google Gemini REST API and get data from body
        $response = $this->guzzleClient->request('POST', $this->urlAPIModel, [
            'headers' => $this->headersJSON,
            'json' => $this->bodyJSON
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);



        // Get text message, update chat history and return response
        $responseTextMessage = $responseData['candidates'][0]['content']['parts'][0]['text'];

        $this->addResponseToChatHistory($responseTextMessage, $responseData);

        return $responseTextMessage;
    }

    private function addResponseToChatHistory($message, $responseData){
        // Add last response to chat history
        $this->newTextMessageJSON['parts'][0]['text'] = $message;
        $this->newTextMessageJSON['role'] = "model";

        array_push($this->chatHistoryJSON, $this->newTextMessageJSON);

        //Update tokens of chat
        $this->updateTokens($responseData);
    }

    private function updateTokens($responseData){
        // Update token counter
        $this->promptTokenCount = $responseData['usageMetadata']['promptTokenCount'];
        $this->candidatesTokenCount = $responseData['usageMetadata']['candidatesTokenCount'];
        $this->totalTokenCount = $responseData['usageMetadata']['totalTokenCount'];
        $this->totalTokenHistoryChatCount += $this->totalTokenCount;
    }

    public function updateGeminiModelConfig($newGeminiModelConfig)
    {
        $this->geminiModelConfig = array_merge($this->geminiModelConfig, $newGeminiModelConfig);
    }


}
