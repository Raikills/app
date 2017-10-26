<?php

use App\Modules\Platform\Models\Option;

use Nova\Auth\Access\AuthorizationException;
use Nova\Auth\AuthenticationException;
use Nova\Database\ORM\ModelNotFoundException;
use Nova\Session\TokenMismatchException;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


//--------------------------------------------------------------------------
// Application Error Logger
//--------------------------------------------------------------------------

Log::useFiles(STORAGE_PATH .'logs' .DS .'framework.log');

//--------------------------------------------------------------------------
// Application Error Handler
//--------------------------------------------------------------------------

App::error(function (Exception $e, $code)
{
    static $dontReport = array(
        'Nova\Auth\AuthenticationException',
        'Nova\Database\ORM\ModelNotFoundException',
        'Nova\Session\TokenMismatchException',
        'Nova\Validation\ValidationException',
        'Symfony\Component\HttpKernel\Exception\HttpException',
    );

    static $dontFlash = array('password', 'password_confirmation');

    // Report the Exception.
    $shouldReport = true;

    foreach ($dontReport as $type) {
        if ($e instanceof $type) {
            $shouldReport = false;

            break;
        }
    }

    if ($shouldReport) {
        Log::error($e);
    }

    // Prepare the exception.
    if ($e instanceof ModelNotFoundException) {
        $e = new NotFoundHttpException($e->getMessage(), $e);
    } else if ($e instanceof AuthorizationException) {
        $e = new HttpException(403, $e->getMessage());
    }

    $request = Request::instance();

    // AJAX/API processing.
    if ($request->ajax() || $request->wantsJson() || $request->is('api/*')) {
        $headers = array();

        if ($e instanceof AuthenticationException) {
            $code = 401;
        } else if ($e instanceof HttpException) {
            $code = $e->getStatusCode();

            $headers = $e->getHeaders();
        } else {
            $code = 403;
        }

        return Response::json(array('error' => $e->getMessage()), $code, $headers);
    }

    // Standard processing.
    else if ($e instanceof TokenMismatchException) {
        return Redirect::back()
            ->withInput($request->except($dontFlash))
            ->withStatus(__('Validation Token has expired. Please try again!'), 'danger');
    } else if ($e instanceof AuthenticationException) {
        $guards = $e->guards();

        // We will use the first guard.
        $guard = array_shift($guards);

        $uri = Config::get("auth.guards.{$guard}.paths.authorize", 'login');

        return Redirect::to($uri);
    } else if ($e instanceof HttpException) {
        $code = $e->getStatusCode();

        if (View::exists('Errors/' .$code)) {
            $view = View::makeLayout('Default', 'Bootstrap')
                ->shares('title', 'Error ' .$code)
                ->nest('content', 'Errors/' .$code, array('exception' => $e));

            return Response::make($view->render(), $code, $e->getHeaders());
        }
    }
});

//--------------------------------------------------------------------------
// Maintenance Mode Handler
//--------------------------------------------------------------------------

App::down(function ()
{
    return Response::make("Be right back!", 503);
});

//--------------------------------------------------------------------------
// Load The Options
//--------------------------------------------------------------------------

if (CONFIG_STORE === 'database') {
    // Retrieve the Option items, caching them for 24 hours.
    $options = Cache::remember('system_options', 1440, function ()
    {
        return Option::getResults();
    });

    // Setup the information stored on the Option instances into Configuration.
    foreach ($options as $option) {
        list ($key, $value) = $option->getConfigItem();

        Config::set($key, $value);
    }
}

// If the CONFIG_STORE is not in 'files' mode, go Exception.
else if(CONFIG_STORE !== 'files') {
    throw new InvalidArgumentException('Invalid Config Store type.');
}

//--------------------------------------------------------------------------
// Boot Stage Customization
//--------------------------------------------------------------------------

/**
 * Create a constant for the URL of the site.
 */
define('SITEURL', $app['config']['app.url']);

/**
 * Define relative base path.
 */
define('DIR', $app['config']['app.path']);

/**
 * Create a constant for the name of the site.
 */
define('SITETITLE', $app['config']['app.name']);

/**
 * Set a default language.
 */
define('LANGUAGE_CODE', $app['config']['app.locale']);

/**
 * Set the default theme.
 */
define('THEME', $app['config']['app.theme']);

/**
 * Set a Site administrator email address.
 */
define('SITEEMAIL', $app['config']['app.email']);
