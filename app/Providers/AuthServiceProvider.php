<?php

namespace App\Providers;

use App\Models\Caso;
use App\Models\Alerta;
use App\Policies\CasoPolicy;
use App\Policies\AlertaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Caso::class => CasoPolicy::class,
        Alerta::class => AlertaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
