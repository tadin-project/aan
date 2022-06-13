<?php

namespace Config;

use App\Controllers\API\Data;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.


// =============== Begin Auth ===============
$routes->get('/', 'Auth::index');
$routes->post('/login', 'Auth::login_proses');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::register_proses');
$routes->get('/forgot-password', 'Auth::forgot_password');
$routes->post('/forgot-password', 'Auth::forgot_proses');
$routes->get('/reset-password', 'Auth::reset_password');
$routes->post('/reset-password', 'Auth::reset_proses');
$routes->get('/logout', 'Auth::logout');
// ================ End Auth ================

// =============== Begin Dashboard ===============
$routes->group('dashboard', static function ($routes) {
    $routes->get('', 'Dashboard::index');
    $routes->post('get_data', 'Dashboard::get_data');
    $routes->get('get_device', 'Dashboard::get_device');
    $routes->get('get_data_grafik', 'Dashboard::get_data_grafik');
});
// ================ End Dashboard ================

// =============== Begin User ===============
$routes->group('ms-user', static function ($routes) {
    $routes->get('', 'MsUser::index');
    $routes->post('', 'MsUser::save');
    $routes->delete('(:any)', 'MsUser::hapus/$1');
    $routes->post('get_data', 'MsUser::get_data');
    $routes->get('akses', 'MsUser::get_akses');
    $routes->post('akses', 'MsUser::save_akses');
});
// ================ End User ================

// =============== Begin Group ===============
$routes->group('ms-group', static function ($routes) {
    $routes->get('', 'MsGroup::index');
    $routes->post('', 'MsGroup::save');
    $routes->delete('(:any)', 'MsGroup::hapus/$1');
    $routes->post('get-data', 'MsGroup::get_data');
    $routes->get('akses', 'MsGroup::get_menu');
    $routes->post('akses', 'MsGroup::save_akses');
});
// ================ End Group ================

// =============== Begin Setting ===============
$routes->group('setting', static function ($routes) {
    $routes->get('', 'Setting::index');
    $routes->post('', 'Setting::save');
    $routes->post('get-data', 'Setting::get_data');
});
// ================ End Setting ================

// =============== Begin API ===============
$routes->group('api', static function ($routes) {
    $routes->post('data', [Data::class, 'store']);
});
// ================ End API ================
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
