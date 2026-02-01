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
     * Mapeamento de modelos para policies da aplicação.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Caso::class => CasoPolicy::class,
        Alerta::class => AlertaPolicy::class,
    ];

    /**
     * Registe quaisquer serviços de autenticação/autorização.
     */
    public function boot(): void
    {
        //
    }
}
