<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('scripts/(:segment)\.ps1', 'Ps1::serve/$1/ps1');
$routes->get('scripts/(:segment)\.sh', 'Ps1::serve/$1/sh');
$routes->get('scripts/(:segment)', 'Script::detail/$1');

// Admin Routes
$routes->group('admin', static function ($routes) {
    $routes->get('login', 'Admin::login');
    $routes->post('login', 'Admin::processLogin');
    $routes->get('logout', 'Admin::logout');

    $routes->group('scripts', ['filter' => 'auth'], static function ($routes) {
        $routes->get('/', 'Admin\Scripts::index');
        $routes->get('new', 'Admin\Scripts::new');
        $routes->post('new', 'Admin\Scripts::create');
        $routes->get('(:segment)/edit', 'Admin\Scripts::edit/$1');
        $routes->post('(:segment)/edit', 'Admin\Scripts::update/$1');
        $routes->post('(:segment)/delete', 'Admin\Scripts::delete/$1');
        // File management routes
        $routes->get('files', 'Admin\Scripts::files');
        $routes->post('files/upload', 'Admin\Scripts::uploadFile');
        $routes->post('files/(:segment)/generate', 'Admin\Scripts::generateFromFile/$1');
        $routes->post('files/(:segment)/delete', 'Admin\Scripts::deleteFile/$1');
    });

    // Default admin page redirects to scripts
    $routes->get('/', 'Admin::index', ['filter' => 'auth']);
});
