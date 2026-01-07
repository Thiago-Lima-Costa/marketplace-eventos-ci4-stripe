<?php

use App\Controllers\Api\ApiEventLayoutController;
use App\Controllers\Api\ApiReserveSeatsController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\DashboardController;
use App\Controllers\EventsController;
use App\Controllers\HomeController;
use App\Controllers\OrdersController;
use App\Controllers\OrganizerController;
use App\Controllers\ShowEventController;
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
        $routes->get('new', [EventsController::class, 'new'], ['as' => 'dashboard.events.new']);
        $routes->get('show/(:segment)', [EventsController::class, 'show/$1'], ['as' => 'dashboard.events.show']);
        $routes->post('create', [EventsController::class, 'create'], ['as' => 'dashboard.events.create']);
    });

    $routes->group('organizer', static function ($routes) {
        $routes->get('/', [OrganizerController::class, 'edit'], ['as' => 'dashboard.organizer']);
        $routes->get('panel', [OrganizerController::class, 'panel'], ['as' => 'dashboard.organizer.panel']);
        $routes->post('create', [OrganizerController::class, 'create'], ['as' => 'dashboard.organizer.create.account']);
        $routes->put('check', [OrganizerController::class, 'check'], ['as' => 'dashboard.organizer.check.account']);
    });

    $routes->group('orders', static function ($routes) {
        $routes->get('/', [OrdersController::class, 'index'], ['as' => 'dashboard.orders']);
        $routes->get('show/(:segment)', [OrdersController::class, 'show/$1'], ['as' => 'dashboard.orders.show']);
         $routes->get('print/(:segment)', [OrdersController::class, 'print/$1'], ['as' => 'dashboard.orders.print']);
    });
});

$routes->group('api', static function ($routes) {

    $routes->group('events', static function ($routes) {

        $routes->get('layout/(:segment)', [ApiEventLayoutController::class, 'layout/$1'], ['as' => 'api.events.layout']);

    });

    $routes->group('seats', static function ($routes) {

        $routes->post('action', [ApiReserveSeatsController::class, 'action'], ['as' => 'api.seats.action']);

    });

});

$routes->group('events', static function ($routes) {

    $routes->get('(:segment)', [ShowEventController::class, 'index/$1'], ['as' => 'events.show']);

});

$routes->group('cart', static function ($routes) {

    $routes->get('/', [CartController::class, 'index'], ['as' => 'cart']);
    $routes->delete('destroy/(:num)', [CartController::class, 'destroy/$1'], ['as' => 'cart.destroy']);

});

$routes->group('checkout', static function ($routes) {

    $routes->get('/', [CheckoutController::class, 'index'], ['as' => 'checkout']);
    $routes->get('success', [CheckoutController::class, 'success'], ['as' => 'checkout.success']);

});
