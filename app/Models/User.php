<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'remember_token',
        'active',
        'access',
        'tentativas',
        'ultimo_login',
        'funcao_id',
        'two_factor_required'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $model->user_add = Auth()->User()->id;
        });

        self::updating(function($model){
            $model->user_upd = Auth()->User()->id;
        });
    }


    /************************ Relationships ************************/

    

    /************************ Methods ************************/


    public function scopeSearch($query, $filter = NULL)
    {
        $data = self::query()->orderBy('name');

        return $data;
    }



    /************************ Methods Permissions ************************/

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function roleAdd($role)
    {
        if(is_string($role))
        {
            $role = Role::where('name','=',$role)->firstOrFail();
        }

        if($this->existsRole($role))
        {
            return;
        }

        return $this->roles()->attach($role);
    }

    public function existsRole($role)
    {
        if(is_string($role))
        {
            $role = Role::where('name','=',$role)->firstOrFail();
        }
        return (boolean) $this->roles()->find($role->id);
    }

    public function roleRemove($role)
    {
        if(is_string($role))
        {
            $role = Role::where('name','=',$role)->firstOrFail();
        }
        return $this->roles()->detach($role);
    }

    public function isSuperAdmin(User $user)
    {
        // if($user->hasRoles('Admin'))
        //         return true;

        return $this->hasRoles('Admin');
    }

    /*public function hasPermission(Permission $permission)
    {
        return $this->hasRoles($permission->roles);
    }*/

    public function hasRoles($roles)
    {
        if( is_array($roles) || is_object($roles) )
        {
            return !! $roles->intersect($this->roles)->count();
        }
        return $this->roles->contains('name', $roles);
    }
}
