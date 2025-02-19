<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Contracts;

interface GeminiLimitTokenServiceInterface
{
    /**
     * Method to check if the user can make a request to gemini
     * @param $user
     * @return bool
     */
    public function canMakeRequest ($user) : bool;
    /**
     * Method to update the usage of the user
     * @param $user : User
     * @param $tokens : int 
     * @return mixed
     */
    public function updateUsage($user, $tokens);
}