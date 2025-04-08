<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Traits;

trait GeminiConfigAndPropertiesJSONStructures
{
    //Gemini 1.5
    public const GEMINI_FLASH_001 = "gemini-1.5-flash";//Present
    public const GEMINI_PRO_001 = "gemini-1.5-pro";//Present
    public const GEMINI_FLASH_8B = "gemini-1.5-flash-8b";//Present
    public const LEARNLM_1_5_PRO_EXP = "learnlm-1.5-pro-experimental";//Present
    //Gemini 2
    public const GEMINI_FLASH_V2_0_EXP = "gemini-2.0-flash-exp";//Present
    public const GEMINI_V2 = "gemini-2.0-flash";//Present
    public const GEMINI_V2_FLASH_LITE = "gemini-2.0-flash-lite";//Present
    public const GEMINI_V2_FLASH_LITE_PREVIEW = "gemini-2.0-flash-lite-preview-02-05";
    public const GEMINI_FLASH_V2_0_THINKING_EXP = "gemini-2.0-flash-thinking-exp-01-21";//Present
    public const GEMINI_FLASH_V2_0_EXP_IMAGE_GENERATION = "gemini-2.0-flash-exp-image-generation";//Present
    //Gemini 2.5
    public const GEMINI_2_5_PRO_PREVIEW = "gemini-2.5-pro-preview-03-25";//Present
    

    // Model Config structure that represente the JSON config and has default config
    private $modelConfigJSON = [
        "temperature" => 1,
        "topK" => 40,
        "topP" => 0.95,
        "maxOutputTokens" => 8192,
        "responseMimeType" => "text/plain"
    ];

    // Response schema structure that represente the JSON schema response when you use JSON MODE
    private $responseSchema = [];
    private $currentGeminiModel = "";

    //Stable Models
    private $urlAPItoGeminiFlash001 = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=";
    private $urlAPItoGeminiFlash8B = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-8b:generateContent?key=";
    private $urlAPItoGeminiPro001 = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent?key=";
    private $urlAPItoGeminiV2 = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=";
    private $urlAPItoGeminiV2FlashLite = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-lite:generateContent?key=";
    //Experimental models Models
    private $urlAPItoGeminiFlashV2Exp = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp:generateContent?key=";
    private $urlAPItoGeminiFlashV2ThinkingExp = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-thinking-exp-01-21:generateContent?key=";
    private $urlAPItoLearnLMProExp = "https://generativelanguage.googleapis.com/v1beta/models/learnlm-1.5-pro-experimental:generateContent?key=";
    private $urlAPItoGeminiV2FlashLitePreview = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-lite-preview-02-05:generateContent?key=";
    private $urlAPItoGeminiFlashV2ExpImageGeneration = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp-image-generation:generateContent?key=";
    private $urlAPItoGemini25ProPreview = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-pro-preview-03-25:generateContent?key=";

    // ! TODO: Verivy if this model are available
    //private $urlAPItoGeminiFlash002 = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-002:generateContent?key=";
    //private $urlAPItoGeminiPro002 = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-002:generateContent?key=";

}
