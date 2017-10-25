<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Nova\Http\Request;


// The Default Routes
Route::any('/', function ()
{
    $view = View::make('Default')
        ->shares('title', __('Welcome'))
        ->with('content', __('Yep! It works.'));
    return View::makeLayout('Welcome')->with('content', $view);
});

/**
 * The Language Changer.
 */
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


/**
 * Show the PHP information.
 */
Route::get('phpinfo', function ()
{
    ob_start();

    phpinfo();

    return Response::make(ob_get_clean(), 200);
});
