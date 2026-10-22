<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = [
        'name','description'
    ];

    /************************ Relationships ************************/

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    

    /************************ Methods ************************/

    public function existsPermission($permission)
    {
        if(is_string($permission))
        {
            $permission = Permission::where('name','=',$permission)->firstOrFail();
        }

        return (boolean) $this->permissions()->find($permission->id);
    }


    public function permissionAdd($permission)
    {
        if(is_string($permission))
        {
            $permission = Role::where('name','=',$permission)->firstOrFail();
        }

        if($this->existsPermission($permission))
        {
            return;
        }

        return $this->permissions()->attach($permission);
    }
    

    public function permissionRemove($permission)
    {
        if(is_string($permission))
        {
            $permission = Permission::where('name','=',$permission)->firstOrFail();
        }

        return $this->permissions()->detach($permission);
    }

    public function search($filter = null)
    {
        return $this->where(function ($query) use ($filter) {
            if($filter){
                $query->where('name', 'LIKE', "%$filter%");
                $query->orwhere('description', 'LIKE', "%$filter%");                        
            }
        })->paginate();
    }
}
