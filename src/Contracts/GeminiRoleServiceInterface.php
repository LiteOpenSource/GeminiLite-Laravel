<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Contracts;

interface GeminiRoleServiceInterface
{
    /**
     * Method to assign a role to a user
     * @param string $roleId
     * @param string $roleName
     * @return bool
     */
    public function assignRole(string $roleId, string $roleName): bool;

}