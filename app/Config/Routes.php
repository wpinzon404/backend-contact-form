<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', function (RouteCollection $routes) {
    $routes->options('contacts',        'ContactController::preflight');
    $routes->options('contacts/(:num)', 'ContactController::preflight');
    $routes->resource('contacts', ['controller' => 'ContactController']);
});
