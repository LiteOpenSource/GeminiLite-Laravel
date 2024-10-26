<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Contracts;

interface GeminiServiceInterface
{
    //TODO: Change mixed return types to appropriate types
    public function newChat(): mixed;

    //TODO: Change mixed return types to appropriate types
    public function setGeminiModelConfig($temperature, $topK, $topP, $maxOutputTokens, $responseMimeType, $geminiChatinstance);

    //TODO: Change mixed return types to appropriate types
    public function getGeminiModelConfig(): mixed;

}
