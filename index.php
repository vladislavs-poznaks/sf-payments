<?php

use App\Http\Controllers\PaymentsController;
use App\Http\Request;

require_once 'vendor/autoload.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/api/payment', [PaymentsController::class, 'store']);
});

$request = new Request();
$response = $dispatcher->dispatch($request->method(), $request->uri());

switch ($response[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo 'NOT FOUND';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo 'NOT ALLOWED';
        break;
    case FastRoute\Dispatcher::FOUND:
        [$controller, $method] = $response[1];

        $vars = $response[2] ?? [];
        echo (new $controller)->{$method}($vars);

        break;
}
