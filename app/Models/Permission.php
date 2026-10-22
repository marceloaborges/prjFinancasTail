<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = [
        'permission_group_id',
        'name',
        'description'
    ];    

    /************************ Relationships ************************/

    public function group()
    {
        return $this->belongsTo(PermissionGroup::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    

    /************************ Methods ************************/
}
