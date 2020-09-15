<?php

namespace pierresilva\AccessControl\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(env('DB_DYNAMIC') ? 'dynamic' : env('DB_CONNECTION'));
    }

    /**
     * Permissions can belong to many roles.
     *
     * @return Model
     */
    public function roles()
    {
        return $this->belongsToMany('\pierresilva\AccessControl\Models\Role')->withTimestamps();
    }

    /**
     * Assigns the given role to the permission.
     *
     * @param int $roleId
     *
     * @return bool
     */
    public function assignRole($roleId = null)
    {
        $roles = $this->roles;

        if (!$roles->contains($roleId)) {
            return $this->roles()->attach($roleId);
        }

        return false;
    }

    /**
     * Revokes the given role from the permission.
     *
     * @param int $roleId
     *
     * @return bool
     */
    public function revokeRole($roleId = '')
    {
        return $this->roles()->detach($roleId);
    }

    /**
     * Syncs the given role(s) with the permission.
     *
     * @param array $roleIds
     *
     * @return bool
     */
    public function syncRoles(array $roleIds = [])
    {
        return $this->roles()->sync($roleIds);
    }

    /**
     * Revokes all roles from the permission.
     *
     * @return bool
     */
    public function revokeAllRoles()
    {
        return $this->roles()->detach();
    }
}
