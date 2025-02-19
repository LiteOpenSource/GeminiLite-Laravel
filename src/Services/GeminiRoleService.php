<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Services;

use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRole;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiRoleServiceInterface;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRoleAssignment;

class GeminiRoleService  implements GeminiRoleServiceInterface
{
    /**
     * Method to assign a gemini role to a user
     * @param $user
     * @param $role
     * @return bool
     */
    public function assignRole($user, $role): bool
    {
         $roleModel = $role instanceof GeminiLiteRole
            ? $role
            :( is_string($role)? GeminiLiteRole::where('name', $role)->firstOrFail(): GeminiLiteRole::where('id', $role)->firstOrFail());

        if (GeminiLiteRoleAssignment::where('id', $roleModel->id)
                ->where('user_id', $user->id)
                ->exists()
            ) {
            return false;
        }
        //$user->roles()->attach($roleModel->id, ['active' => $active]);
        GeminiLiteRoleAssignment::create([
            'user_id' => $user->id,
            'role_id' => $roleModel->id,
            'active' => true
        ]);

        return true;
    }
}
