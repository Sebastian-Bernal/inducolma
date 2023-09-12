<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //gate para la autorizacion de los roles, entrada para administrador

        Gate::define('admin', function ($user) {
            return $user->rol_id >= 3;
        });

        //Gate para usuario con rol entrada de maderas
        Gate::define('entrada-maderas', function ($user) {
            return $user->rol_id === 1;
        });

        //Gate para usuario con rol entrada de maderas
        Gate::define('ver-entrada', function ($user) {
            return $user->rol_id === 1;
        });

        //Gate para usuario cubicaje
        Gate::define('cubicaje',function($user){
            return $user->rol_id === 2;
        });

        // gate para usuario proceso
        Gate::define('procesos',function($user){
            return $user->rol_id === 2;
        });

    }


}
