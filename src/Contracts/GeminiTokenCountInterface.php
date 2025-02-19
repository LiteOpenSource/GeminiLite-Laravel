<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Contracts;

interface GeminiTokenCountInterface
{
    //TODO: Change mixed return types to appropriate types
    /**
     * Method to count the number of tokens in a text
     * @param $content : string
     * @return mixed
     */
    public function coutTextTokens($content): mixed;
    /**
     * Method to count the number of tokens in a text with an image
     * @param $text : string
     * @param $imagePath : string
     * @return mixed
     */
    public function countTokensWithImage(string $text, string $imagePath): mixed ;

}