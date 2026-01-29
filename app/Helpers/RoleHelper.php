<?php

if (!function_exists('user_has_role')) {
    /**
     * Check if user (array from session or User model) has the given role.
     *
     * @param array|object $user
     * @param string $role
     * @return bool
     */
    function user_has_role($user, string $role): bool
    {
        if (!$user) {
            return false;
        }
        $roles = is_array($user) ? ($user['roles'] ?? []) : $user->getRolesList();
        return is_array($roles) && in_array($role, $roles, true);
    }
}

if (!function_exists('user_has_any_role')) {
    /**
     * Check if user has any of the given roles.
     *
     * @param array|object $user
     * @param array $roles
     * @return bool
     */
    function user_has_any_role($user, array $roles): bool
    {
        if (!$user) {
            return false;
        }
        $userRoles = is_array($user) ? ($user['roles'] ?? []) : $user->getRolesList();
        if (!is_array($userRoles)) {
            return false;
        }
        return count(array_intersect($roles, $userRoles)) > 0;
    }
}

if (!function_exists('user_can_data_monitoring')) {
    /**
     * Check if user can access a Data Monitoring menu (merged from all roles).
     *
     * @param array|object $user
     * @param string $permission One of: stock, adjustment_stock, data_monitoring, list_item_pricing
     * @return bool
     */
    function user_can_data_monitoring($user, string $permission): bool
    {
        if (!$user) {
            return false;
        }
        $userRoles = is_array($user) ? ($user['roles'] ?? []) : $user->getRolesList();
        if (!is_array($userRoles)) {
            return false;
        }
        $config = config('roles.data_monitoring_permissions', []);
        foreach ($userRoles as $role) {
            $perms = $config[$role] ?? null;
            if (!$perms) {
                continue;
            }
            if (!empty($perms['all'])) {
                return true;
            }
            if (!empty($perms[$permission])) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('user_roles_list')) {
    /**
     * Get user roles as array (for display).
     *
     * @param array|object $user
     * @return array
     */
    function user_roles_list($user): array
    {
        if (!$user) {
            return [];
        }
        $roles = is_array($user) ? ($user['roles'] ?? []) : $user->getRolesList();
        return is_array($roles) ? $roles : [];
    }
}
