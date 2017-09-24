<?php

/**
 * Routes - all standard Routes are defined here.
 *
 * @author David Carr - dave@daveismyname.com
 * @version 3.0
 *
 */

use Nova\Http\Request;


/** Define static routes. */

// Default Routing
Route::any('/', function ()
{
    $content = __('Yep! It works.');

    $view = View::make('Default')
        ->shares('title', __('Welcome'))
        ->withContent($content);

    return View::makeLayout('Welcome')->withContent($view);
});


// The Language Changer.
Route::get('language/{language}', function (Request $request, $language)
{
    $url = Config::get('app.url');

    $languages = Config::get('languages');

    if (array_key_exists($language, $languages) && Str::startsWith($request->header('referer'), $url)) {
        Session::set('language', $language);

        // Store also the current Language in a Cookie lasting five years.
        Cookie::queue(PREFIX .'language', $language, Cookie::FIVEYEARS);
    }

    return Redirect::back();

})->where('language', '([a-z]{2})');


// Show the PHP information
Route::get('phpinfo', function ()
{
    ob_start();

    phpinfo();

    return Response::make(ob_get_clean(), 200);
});

/** End default Routes */
