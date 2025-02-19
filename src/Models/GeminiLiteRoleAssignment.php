<?php
namespace LiteOpenSource\GeminiLiteLaravel\Src\Models;

use Illuminate\Database\Eloquent\Model;

class GeminiLiteRoleAssignment extends Model
{
    protected $table = 'gemini_lite_role_assignments';

    protected $fillable = [
        'user_id',
        'role_id',
        'active',
    ];

    // Relación: Una asignación pertenece a un usuario
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    // Relación: Una asignación pertenece a un rol
    public function role()
    {
        return $this->belongsTo(GeminiLiteRole::class, 'role_id');
    }
}
