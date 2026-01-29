<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'username',
        'password',
        'roles',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'roles' => 'array',
    ];

    /**
     * Get roles as array (never null).
     */
    public function getRolesList(): array
    {
        $roles = $this->roles;
        return is_array($roles) ? $roles : [];
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        $roles = $this->getRolesList();
        return in_array($role, $roles, true);
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        $userRoles = $this->getRolesList();
        return count(array_intersect($roles, $userRoles)) > 0;
    }

    /**
     * Check if user can access a Data Monitoring menu (merged from all roles).
     */
    public function canAccessDataMonitoring(string $permission): bool
    {
        $userRoles = $this->getRolesList();
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

    /**
     * Get merged Data Monitoring permissions (union of all roles).
     */
    public function getDataMonitoringPermissions(): array
    {
        $userRoles = $this->getRolesList();
        $config = config('roles.data_monitoring_permissions', []);
        $merged = ['stock' => false, 'adjustment_stock' => false, 'data_monitoring' => false, 'list_item_pricing' => false];
        foreach ($userRoles as $role) {
            $perms = $config[$role] ?? null;
            if (!$perms) {
                continue;
            }
            if (!empty($perms['all'])) {
                return ['stock' => true, 'adjustment_stock' => true, 'data_monitoring' => true, 'list_item_pricing' => true];
            }
            foreach (['stock', 'adjustment_stock', 'data_monitoring', 'list_item_pricing'] as $key) {
                if (!empty($perms[$key])) {
                    $merged[$key] = true;
                }
            }
        }
        return $merged;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
