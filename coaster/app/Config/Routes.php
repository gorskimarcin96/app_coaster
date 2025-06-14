<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group(
    'api',
    function ($routes) {
        $routes->group(
            'coasters',
            function ($routes) {
                $routes->post('', 'Coaster\UI\Http\Controller\RegisterCoasterController::__invoke');
                $routes->get('', 'Coaster\UI\Http\Controller\GetCoastersController::__invoke');
                $routes->get('(:segment)', 'Coaster\UI\Http\Controller\GetCoasterController::__invoke/$1');
            },
        );
    },
);
