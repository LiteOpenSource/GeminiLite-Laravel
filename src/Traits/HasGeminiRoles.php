<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use LiteOpenSource\GeminiLiteLaravel\Src\Services\GeminiRoleService;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRequestLog;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRole;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRoleAssignment;
use LiteOpenSource\GeminiLiteLaravel\Src\Services\GeminiLimitTokenService;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteUsage;

trait HasGeminiRoles
{
    /**
     * Method to check if the user has a role in gemini
     * @param $role
     * @return bool
     */
    public function isActiveInGemini() : bool{
        $assigment = GeminiLiteRoleAssignment::where('user_id', $this->id)->firstOrFail();
        return $assigment->active;
    }

    /**
     * Method to check if the user has a role in gemini
     * @param $role
     * @return bool
     */
    public function canMakeRequestToGemini(): bool
    {
        return app(GeminiLimitTokenService::class)->canMakeRequest($this);
    }

    /** 
     * Method to assign a gemini role to a user, you can pass the role as a string or an integer
     * @param $role : string | int
     * @return bool
    */
     public function assignGeminiRole($role, bool $active = true): bool
    {
        return app(GeminiRoleService::class)->assignRole($this, $role, $active);
    }

    /**
     * Method to log the request made by the user
     * @param $requestType
     * @param $consumedTokens
     * @param $requestSuccessful
     * @param $requestData
     * @param $responseData
     * @return mixed
     */
    public function storeGeminiRequest($requestType, $consumedTokens, $requestSuccessful, $requestData, $responseData)
    {
        app(GeminiLimitTokenService::class)->logRequest($this, $requestType, $consumedTokens, $requestSuccessful, $requestData, $responseData);
    }
    /**
     * Method to update the usage tracking of the user
     * @param $tokens
     * @return mixed
     */
    public function updateUsageTracking( $tokens){
        app(GeminiLimitTokenService::class)->updateUsage($this, $tokens);

    }


}