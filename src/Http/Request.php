<?php

namespace App\Http;

class Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    const ALLOWED_HTTP_METHODS = [
        'GET', 'POST', 'PUT', 'PATCH', 'DELETE'
    ];

    protected static $instance = null;

    public static function getInstance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    protected function __construct() {}

    protected function __clone() {}

    public function method(): string
    {
        //Method spoofing
        if (isset($_POST['_method'])) {
            return in_array($_POST['_method'], Request::ALLOWED_HTTP_METHODS)
                ? $_POST['_method']
                : $_SERVER['REQUEST_METHOD'];
        }

        return $_SERVER['REQUEST_METHOD'];
    }

    public function uri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        return rawurldecode($uri);
    }

    public function getAll(): array
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}