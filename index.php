<?php

use App\Http\Controllers\PaymentsController;
use App\Http\Request;

$container = require __DIR__ . '/bootstrap.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute(Request::METHOD_POST, '/api/payment', [PaymentsController::class, 'store']);
});

$route = $dispatcher->dispatch(Request::method(), Request::uri());

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

        try {
            echo $container->call($route[1], $route[2] ?? []);
        } catch (\App\Http\Exceptions\ValidationException $exception) {
            echo \App\Http\Response::json($exception->request->errors(), $exception->request->getHttpErrorCode());
        } catch (Exception $exception) {
            $logger = $container->get(\App\Loggers\Logger::class);

            $logger::error($exception);

            echo \App\Http\Response::json([
                'error' => $exception->getMessage(),
                'trace' => $exception->getTrace()
            ], \App\Http\HttpCode::INTERNAL_ERROR);
        }

        break;
}
