<?php

use App\Controllers\DashboardController;
use App\Controllers\EventsController;
use App\Controllers\HomeController;
use App\Controllers\OrganizerController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [HomeController::class, 'index'], ['as' => 'home']);

service('auth')->routes($routes);

$routes->group('dashboard', static function ($routes) {

    $routes->get('/', [DashboardController::class, 'index'], ['as' => 'dashboard']);


    $routes->group('events', ['filter' => 'organizer'], static function ($routes) {
        $routes->get('/', [EventsController::class, 'index'], ['as' => 'dashboard.events']);
    });


    $routes->group('organizer', static function ($routes) {
        $routes->get('/', [OrganizerController::class, 'edit'], ['as' => 'dashboard.organizer']);
        $routes->get('panel', [OrganizerController::class, 'panel'], ['as' => 'dashboard.organizer.panel']);
        $routes->post('create', [OrganizerController::class, 'create'], ['as' => 'dashboard.organizer.create.account']);
        $routes->put('check', [OrganizerController::class, 'check'], ['as' => 'dashboard.organizer.check.account']);
    });
});
