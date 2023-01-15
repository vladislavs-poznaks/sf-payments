<?php

use App\Http\Controllers\PaymentsController;
use App\Http\Request;

$container = require __DIR__ . '/bootstrap.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute(Request::METHOD_POST, '/api/payment', [PaymentsController::class, 'store']);
});

$request = Request::getInstance();

$route = $dispatcher->dispatch($request->method(), $request->uri());

switch ($route[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        break;
    case FastRoute\Dispatcher::FOUND:
        [$controller, $method] = $route[1];

        $vars = $response[2] ?? [];

        echo $container->call($route[1], $route[2] ?? []);

        break;
}
