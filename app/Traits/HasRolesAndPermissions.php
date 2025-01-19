<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

trait HasRolesAndPermissions
{
    public function roles(): mixed
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function permissions(): mixed
    {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }

    public function hasRole(...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    public function hasPermission($permission): bool
    {
        return (bool) $this->permissions->where('slug', $permission)->count();
    }

    public function hasPermissionTo($permission): bool
    {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission->slug);
    }

    public function hasPermissionThroughRole($permission): bool
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    public function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('slug', $permissions)->get();
    }

    public function givePermissionsTo(...$permissions): User
    {
        $permissions = $this->getAllPermissions($permissions);
        if ($permissions === null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }

    public function deletePermissions(...$permissions): User
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }

    public function refreshPermissions(...$permissions)
    {
        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }
}
