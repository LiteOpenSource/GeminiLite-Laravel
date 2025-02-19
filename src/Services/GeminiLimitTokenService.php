<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Services;

use DB;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRequestLog;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiLimitTokenServiceInterface;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRole;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRoleAssignment;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteUsage;

class GeminiLimitTokenService implements GeminiLimitTokenServiceInterface
{
    /**
     * Method to check if the user can make a request to gemini
     * @param $user
     * @return bool
     */
    public function canMakeRequest($user): bool
    {
        $usage = GeminiLiteUsage::where("user_id", $user->id)->first();

        if (!$usage) {
            $usage = $this->initializeUsage($user);
        }

        $this->validateLimits($user );

        return $usage->can_make_requests;
    }

    /**
     * Method to initialize the usage of the user
     * @param $user
     * @return mixed
     */
    private function initializeUsage($user)
    {
        return GeminiLiteUsage::create([
            "user_id"=> $user->id,
            'can_make_requests' => true,
            'current_day_tracking_start' => now(),
            'current_month_tracking_start' => now(),
            'completed_requests_today' => 0,
            'completed_requests_this_month' => 0,
            'consumed_tokens_today' => 0,
            'consumed_tokens_this_month' => 0,
            'last_request_completion_time' => null,
        ]);
    }

    /**
     * Method to log the request made by the user
     * @param $user
     * @param $requestType
     * @param $consumedTokens
     * @param $requestSuccessful
     * @param $requestData
     * @param $responseData
     * @return mixed
     */
    public function logRequest($user, $requestType, $consumedTokens, $requestSuccessful, $requestData, $responseData)
    {
        GeminiLiteRequestLog::create([
            'user_id' => $user->id,
            'request_type' => $requestType,
            'consumed_tokens' => $consumedTokens,
            'request_successful' => $requestSuccessful,
            'request_data' => $requestData,
            'response_data' => $responseData,
        ]);
    }
    /**
     * Method to update the usage of the user
     * @param $user : User
     * @param $tokens : int 
     * @return mixed
     */
    public function updateUsage($user, $tokens){
        $geminiUsage = GeminiLiteUsage::where("user_id", $user->id)->first();

        $geminiUsage->increment('completed_requests_today');
        $geminiUsage->increment('completed_requests_this_month');
        $geminiUsage->increment('consumed_tokens_today',$tokens);
        $geminiUsage->increment('consumed_tokens_this_month',$tokens);
        $geminiUsage->update(['last_request_completion_time' => now()]);

        $this->validateLimits($user);

    }

    /**
     * Method to validate the limits of the user
     * @param $user
     * @return bool
     */
    private function validateLimits($user): bool{
        $this->validateDailyLimits($user);
        $this->validateMonthlyLimits($user);

        $usage = GeminiLiteUsage::where("user_id", $user->id)->first();

        $roleId = DB::table('gemini_lite_role_assignments')
                ->where('user_id', $user->id)
                ->pluck('role_id'); // Obtiene solo los IDs de los roles

        $role = GeminiLiteRole::where('id', $roleId)->first();
        if (
            $role->daily_request_limit <= $usage->completed_requests_today || 
            $role->monthly_request_limit <= $usage->completed_requests_user_month ||
            $role->daily_token_limit <= $usage->consumed_tokens_today  ||
            $role->monthly_token_limit <= $usage->consumed_tokens_user_month
        ){
            $usage->update(['can_make_requests' => false]);
            return false;
        }else {
            $usage->update(['can_make_requests' => true]);
            return true;
        }

    }

    /**
     * Method to validate the daily limits of the user
     * @param $user
     * @return bool
     */
    private function validateDailyLimits($user){
        $usage = GeminiLiteUsage::where("user_id", $user->id)->first();
        $lastRequestTime = \Carbon\Carbon::parse($usage->last_request_completion_time);

        if ($lastRequestTime->format('Y-m-d') !== now()->format('Y-m-d')) {
            // Reiniciar contadores diarios
            $usage->update([
                'completed_requests_today' => 0,
                'consumed_tokens_today' => 0,
                'current_day_tracking_start' => now()->toDateTimeString(), // Guardar como string
            ]);
        }
    }
    /**
     * Method to validate the monthly limits of the user
     * @param $user
     * @return bool
     */
    private function validateMonthlyLimits($user){
        $usage = GeminiLiteUsage::where("user_id", $user->id)->first();
        $lastRequestTime = \Carbon\Carbon::parse($usage->last_request_completion_time);

        if ($lastRequestTime->format('Y-m') !== now()->format('Y-m')) {
            // Reiniciar contadores mensuales
            $usage->update([
                'completed_requests_this_month' => 0,
                'consumed_tokens_this_month' => 0,
                'current_month_tracking_start' => now()->toDateTimeString(), // Guardar como string
            ]);
        }
    }
}
