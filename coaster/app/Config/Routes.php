<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group(
    'api',
    fn(RouteCollection $routes) => $routes->group(
        'coasters',
        function (RouteCollection $routes): void {
            $routes->post('', 'Coaster\UI\Http\Controller\RegisterCoasterController::__invoke');
            $routes->get('', 'Coaster\UI\Http\Controller\GetCoastersController::__invoke');
            $routes->get('(:segment)', 'Coaster\UI\Http\Controller\GetCoasterController::__invoke/$1');
            $routes->put('(:segment)', 'Coaster\UI\Http\Controller\ChangeCoasterController::__invoke/$1');
            $routes->post('(:segment)/wagon', 'Coaster\UI\Http\Controller\RegisterWagonController::__invoke/$1');
            $routes->get('(:segment)/wagon', 'Coaster\UI\Http\Controller\GetWagonsController::__invoke/$1');
            $routes->get('(:segment)/wagon/(:segment)', 'Coaster\UI\Http\Controller\GetWagonController::__invoke/$1/$2');
            $routes->delete('(:segment)/wagon/(:segment)', 'Coaster\UI\Http\Controller\DeleteWagonController::__invoke/$1/$2');
        },
    ),
);
