<?php
namespace LiteOpenSource\GeminiLiteLaravel\Src\Models;

use Illuminate\Database\Eloquent\Model;

class GeminiLiteRole extends Model
{
    protected $table = 'gemini_lite_roles';

    protected $fillable = [
        'name',
        'description',
        'daily_request_limit',
        'monthly_request_limit',
        'daily_token_limit',
        'monthly_token_limit',
    ];

    // Relación: Un rol pertenece a muchos usuarios
    public function users()
    {
        return $this->belongsToMany(
            'App\Models\User',
            'gemini_lite_role_assignments',
            'role_id',
            'user_id'
        )->withPivot('active')->withTimestamps();
    }
}
