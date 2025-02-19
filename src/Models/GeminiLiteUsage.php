<?php
namespace LiteOpenSource\GeminiLiteLaravel\Src\Models;
use Illuminate\Database\Eloquent\Model;

class GeminiLiteUsage extends Model
{
    protected $table = 'gemini_lite_usage';

    protected $fillable = [
        'user_id',
        'can_make_requests',
        'current_day_tracking_start',
        'current_month_tracking_start',
        'completed_requests_today',
        'completed_requests_this_month',
        'consumed_tokens_today',
        'consumed_tokens_this_month',
        'last_request_completion_time',
    ];

    // Relación: Un registro de uso pertenece a un usuario
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
