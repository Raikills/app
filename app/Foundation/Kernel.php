<?php

namespace App\Foundation;

use Nova\Foundation\Http\Kernel as HttpKernel;


class Kernel extends HttpKernel
{
    /**
     * The Application's Middleware stack.
     *
     * @var array
     */
    protected $middleware = array(
        'Nova\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Nova\Routing\Middleware\DispatchAssetFiles',
    );

    /**
     * The Application's route Middleware Groups.
     *
     * @var array
     */
    protected $middlewareGroups = array(
        'web' => array(
            'App\Foundation\Middleware\EncryptCookies',
            'Nova\Cookie\Middleware\AddQueuedCookiesToResponse',
            'Nova\Session\Middleware\StartSession',
            'Nova\Foundation\Http\Middleware\SetupLanguage',
            'Nova\View\Middleware\ShareErrorsFromSession',
            'App\Foundation\Middleware\VerifyCsrfToken',
        ),
        'api' => array(
            'throttle:60,1',
        )
    );

    /**
     * The Application's route Middleware.
     *
     * @var array
     */
    protected $routeMiddleware = array(
        'auth'     => 'Nova\Auth\Middleware\Authenticate',
        'guest'    => 'App\Foundation\Middleware\RedirectIfAuthenticated',
        'throttle' => 'Nova\Routing\Middleware\ThrottleRequests',
    );
}
