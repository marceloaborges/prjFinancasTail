<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {        
        Gate::before(function($user, $ability){
            if($user->hasRoles('Admin'))
                return true;             
        });         
        

        // $permissions = Permission::with('roles')->get();
        // // $permissions = Cache::rememberForever('permissions.gates', function () {
        // //     return Permission::with('roles')->get();
        // // });
        // foreach( $permissions as $permission )
        // {           
        //     Gate::define($permission->name, function($user) use ($permission) {
        //         return $user->hasRoles($permission->roles);
        //     });
        // }
    }
}
