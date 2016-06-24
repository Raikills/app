<?php
/**
 * Routes - all standard Routes are defined here.
 *
 * @author David Carr - dave@daveismyname.com
 * @version 3.0
 */


/** Define static routes. */

// Default Routing
Route::any('', 'App\Controllers\Welcome@index');
Route::any('subpage', 'App\Controllers\Welcome@subPage');

// Demo Routes
Route::any('demo/database',            'App\Controllers\Demo@database');
Route::any('demo/password/{password}', 'App\Controllers\Demo@password');
Route::any('demo/events',              'App\Controllers\Demo@events');
Route::any('demo/mailer',              'App\Controllers\Demo@mailer');
Route::any('demo/session',             'App\Controllers\Demo@session');
Route::any('demo/validate',            'App\Controllers\Demo@validate');
Route::any('demo/paginate',            'App\Controllers\Demo@paginate');
Route::any('demo/cache',               'App\Controllers\Demo@cache');

Route::any('demo/request/{param1?}/{param2?}/{slug?}','App\Controllers\Demo@request')
    ->where('slug', '.*');

Route::any('demo/test/{param1}/{param2?}/{param3?}/{slug?}', array(
    'filters' => 'test',
    'uses'    => 'App\Controllers\Demo@test'
))->where('slug', '.*');

// The Framework's Language Changer.
Route::any('language/{language}', array(
    'filters' => 'referer',
    'uses'    => 'App\Controllers\Language@change'
));
/** End default Routes */

