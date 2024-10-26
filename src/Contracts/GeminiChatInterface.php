<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Contracts;

interface GeminiChatInterface
{
    //TODO: Change mixed return types to appropriate types
    public function newPrompt($textPrompt, $fileURI = null, $mimeTipe = null): mixed;

    //TODO: Change mixed return types to appropriate types
    //TODO: Doesn't use by the moment, but should be used in the future
    public function getHistory(): mixed;
}
