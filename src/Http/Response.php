<?php

namespace App\Http;

class Response
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;

    const HTTP_BAD_REQUEST = 400;
    const HTTP_CONFLICT = 409;

    const HTTP_INTERNAL_ERROR = 500;

    public static function json(array $data = [], $httpCode = self::HTTP_OK): string
    {
        http_response_code($httpCode);

        return json_encode($data);
    }
}