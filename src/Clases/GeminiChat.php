<?php
namespace LiteOpenSource\GeminiLiteLaravel\Src\Clases;

use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiChatInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Exception;
use Illuminate\Support\Facades\Log;
use LiteOpenSource\GeminiLiteLaravel\Src\Traits\GeminiConfigAndPropertiesJSONStructures;
use LiteOpenSource\GeminiLiteLaravel\Src\Traits\GeminiRequestAndResponsesJSONStructures;
use LiteOpenSource\GeminiLiteLaravel\Src\Traits\GeminiTokenPropertiesJSONStructures;

class GeminiChat implements GeminiChatInterface
{

    //---------------------------- PROPERTIES SECTION --------------------------
    //---------------------------- PROPERTIES SECTION --------------------------
    private $guzzleClient;

    use GeminiConfigAndPropertiesJSONStructures;
    use GeminiRequestAndResponsesJSONStructures;
    use GeminiTokenPropertiesJSONStructures;

    //---------------------------- CONSTRUCTOR SECTION --------------------------
    //---------------------------- CONSTRUCTOR SECTION --------------------------
    public function __construct($guzzleClient, $secretAPIKey)
    {
        $this->guzzleClient = $guzzleClient;
        $this->addAPIKeyToGeminiModels($secretAPIKey);
        $this->initTokensChatProperties();
        $this->initDefaultConfigGeminiAPIJSON();
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
        Log::info("[ IN GeminiChat ->  newPrompt: ]. Gemini current model config: ", [$this->modelConfigJSON, $this->currentGeminiModel]);

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

    //TODO: I should return a array instead mixed type
    //TODO: Verify if I need to add control error management when request getGeminiModelConfig and this is the first funciotn caller. Maybe this function can return error null parameters.
    //TODO: Thiking about before TODO, I need to verify urlAPI and secretAPIKey.

    public function getGeminiModelConfig(): mixed
    {
        return $this->modelConfigJSON;
    }

    public function setGeminiModelConfig($temperature, $topK, $topP, $maxOutputTokens, $responseMimeType, $responseSchema = null, $currentModel = null)
    {
        $this->modelConfigJSON['temperature'] = $temperature;
        $this->modelConfigJSON['topK'] = $topK;
        $this->modelConfigJSON['topP'] = $topP;
        $this->modelConfigJSON['maxOutputTokens'] = $maxOutputTokens;
        $this->modelConfigJSON['responseMimeType'] = $responseMimeType;

        //Add response schema if provided. When is printed tha it mean that user is using JSON MODE
        if($responseSchema != null){
            $this->responseSchema = $responseSchema;

            $this->modelConfigJSON = array_merge($this->modelConfigJSON, $responseSchema);

            Log::info("[ IN GeminiChat ->  setGeminiModelConfig: ]. Gemini current model config: ", [$this->modelConfigJSON]);
        }


        // TODO: Verify if I need to add urlAPI to change between models HERE
        // TODO: Maybe is better desition crear a specific fuction to change model into interface
    }
    // ! NO URGENT, BUT... WE HAVE TO ADD VALIDATION BECAUSE SOME MODEL HAVE
    // ! ... LIMIT OF TOP K AND TOP P VALUES
    public function changeGeminiModel($geminiModelName){
        if($geminiModelName == null){
            Log::error("SYSTEM THREW:: [GeminiChat -> changeGeminiModel]catch Exception in GeminiAPI.php: Gemini model name is null.");
            return;
        }

        switch ($geminiModelName) {
            case self::GEMINI_FLASH_001:
                $this->currentGeminiModel = $this->urlAPItoGeminiFlash001;
                Log::info("[ IN GeminiChat ->  changeGeminiModel: ]. Gemini model (GEMINI_FLASH_001) changed current model config: ", [$this->currentGeminiModel]);
                break;
            case self::GEMINI_FLASH_002:
                $this->currentGeminiModel = $this->urlAPItoGeminiFlash002;
                Log::info("[ IN GeminiChat ->  changeGeminiModel: ]. Gemini model (GEMINI_FLASH_002) changed current model config: ", [$this->currentGeminiModel]);
                break;
            case self::GEMINI_FLASH_8B:
                $this->currentGeminiModel = $this->urlAPItoGeminiFlash8B;
                Log::info("[ IN GeminiChat ->  changeGeminiModel: ]. Gemini model (GEMINI_FLASH_8B) changed current model config: ", [$this->currentGeminiModel]);
                break;
            case self::GEMINI_PRO_001:
                $this->currentGeminiModel = $this->urlAPItoGeminiPro001;
                Log::info("[ IN GeminiChat ->  changeGeminiModel: ]. Gemini model (GEMINI_PRO_001) changed current model config: ", [$this->currentGeminiModel]);
                break;
            case self::GEMINI_PRO_002:
                $this->currentGeminiModel = $this->urlAPItoGeminiPro002;
                Log::info("[ IN GeminiChat ->  changeGeminiModel: ]. Gemini model (GEMINI_PRO_002) changed current model config: ", [$this->currentGeminiModel]);
                break;

            default:
                Log::error("SYSTEM THREW:: [GeminiChat -> changeGeminiModel]catch Exception in GeminiAPI.php: Gemini model name not found.");
                return;
        }
    }

    //------------------------ OTHER FUNCTIONS SECTION -------------------------
    //------------------------ OTHER FUNCTIONS SECTION -------------------------
    private function initTokensChatProperties()
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
        $this->bodyJSON['generationConfig'] = $this->modelConfigJSON;
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
        $this->bodyJSON['generationConfig'] = $this->modelConfigJSON;
        Log::info("[ IN GeminiChat ->  assemblingJSONTextRequest: ]. bodyJSON: " , [$this->bodyJSON]);

    }

    public function makeTextRequestToGeminiAPI(){
        // Make Request to Google Gemini REST API and get data from body
        Log::info("[ IN GeminiChat ->  makeTextRequestToGeminiAPI: ]. headersJSON" , [$this->headersJSON]);
        Log::info("[ IN GeminiChat ->  makeTextRequestToGeminiAPI: ]. geminiModelConfig" , [$this->modelConfigJSON]);
        Log::info("[ IN GeminiChat ->  makeTextRequestToGeminiAPI: ]. urlAPIModel" , [$this->currentGeminiModel]);


        $response = $this->guzzleClient->request('POST', $this->currentGeminiModel, [
            'headers' => $this->headersJSON,
            'json' => $this->bodyJSON
        ]);

        // ! TODO: NOT URGENT BUT... ADD ERROR HANDLING FOR BAD RESPONSES, OTHERWISE RETURN HTML ERROR INSTEAD JSON ERROR
        $responseData = json_decode($response->getBody()->getContents(), true);

        // Get text message, update chat history and return response
        $responseTextMessage = $responseData['candidates'][0]['content']['parts'][0]['text'];

        $this->addResponseToChatHistory($responseTextMessage, $responseData);

        return $responseTextMessage;
    }

    public function makeFileRequestToGeminiAPI(){
        // Make Request to Google Gemini REST API and get data from body
        $response = $this->guzzleClient->request('POST', $this->currentGeminiModel, [
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

    private function initDefaultConfigGeminiAPIJSON()
    {
        $this->modelConfigJSON['temperature'] = 1;
        $this->modelConfigJSON['topK'] = 40;
        $this->modelConfigJSON['topP'] = 0.95;
        $this->modelConfigJSON['maxOutputTokens'] = 8192;
        $this->modelConfigJSON['responseMimeType'] = "text/plain";

        $this->currentGeminiModel = $this->urlAPItoGeminiFlash001;
    }

    private function addAPIKeyToGeminiModels($secretAPIKey){
        $this->urlAPItoGeminiFlash001 .= $secretAPIKey;
        $this->urlAPItoGeminiFlash002 .= $secretAPIKey;
        $this->urlAPItoGeminiFlash8B .= $secretAPIKey;
        $this->urlAPItoGeminiPro001 .= $secretAPIKey;
        $this->urlAPItoGeminiPro002 .= $secretAPIKey;
    }



}
