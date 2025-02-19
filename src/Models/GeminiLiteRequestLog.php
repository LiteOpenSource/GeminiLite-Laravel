<?php 
namespace LiteOpenSource\GeminiLiteLaravel\Src\Models;
use Illuminate\Database\Eloquent\Model;

class GeminiLiteRequestLog extends Model
{
    protected $table = 'gemini_lite_request_logs';

    protected $fillable = [
        'user_id',
        'request_type',
        'consumed_tokens',
        'request_successful',
        'request_data',
        'response_data',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
    ];

    // Relación: Un log pertenece a un usuario
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
