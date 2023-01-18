<?php

namespace App\Http;

class Response
{
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;

    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_CONFLICT = 409;

    public const HTTP_INTERNAL_ERROR = 500;

    public static function json(array $data = [], $httpCode = self::HTTP_OK): string
    {
        http_response_code($httpCode);

        return json_encode($data);
    }
}
