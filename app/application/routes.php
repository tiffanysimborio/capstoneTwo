<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

Route::controller(array(
        'auth',
        'home',
        'item',
        'transaction',
        'report',
        'category',
        'contact',
        'user',
        'setting',
        'ajax',
        'site'
));



/*
// Create a new Permission
$permission = new \Verify\Models\Permission;
$permission->name = 'view_users';
$permission->name = 'view_roles';
$permission->name = 'add_users';
$permission->name = 'edit_users';
$permission->name = 'delete_users';
$permission->name = 'edit_self';

$permission->name = 'view_categories';
$permission->name = 'add_categories';
$permission->name = 'edit_categories';
$permission->name = 'delete_categories';

$permission->name = 'view_contacts';
$permission->name = 'add_contacts';
$permission->name = 'edit_contacts';
$permission->name = 'delete_contacts';

$permission->name = 'view_groups';

$permission->name = 'view_settings';
$permission->name = 'edit_settings';

$permission->name = 'view_items';
$permission->name = 'view_item_edits';
$permission->name = 'view_checkin';
$permission->name = 'view_checkout';
$permission->name = 'add_checkin';
$permission->name = 'add_checkout';
$permission->name = 'add_items';
$permission->name = 'edit_items';
$permission->name = 'delete_items';
$permission->name = 'upload_item_images';
$permission->name = 'delete_item_images';

$permission->name = 'view_transactions;
$permission->name = 'delete_transactions;
$permission->name = 'edit_transactions;

$permission->save();

// Create a new Role
$role = new Verify\Models\Role;
$role->name = 'Moderator';
$role->level = 7;
$role->save();

// Assign the Permission to the Role
$role->permissions()->sync(array($permission->id));





 */

/*
|--------------------------------------------------------------------------
| Composing
|--------------------------------------------------------------------------
 */
View::composer('layout.index', function($view)
{
    Asset::style('bootstrapcss', 'app/assets/css/bootstrap.min.css');
    Asset::style('datatablescss', 'app/assets/css/jquery.dataTables.css');
    Asset::style('apprisecss', 'app/assets/css/apprise-v2.css');
    Asset::style('style', 'app/assets/css/style.css');

    Asset::script('jquery', 'app/assets/js/jquery-1.9.1.min.js');
    Asset::script('bootstrap', 'app/assets/js/bootstrap.min.js');
    Asset::script('datatables', 'app/assets/js/jquery.dataTables.min.js');
    Asset::script('apprise', 'app/assets/js/apprise-v2.min.js', 'jquery');
});

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to_action('auth@login');
});