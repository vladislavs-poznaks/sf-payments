<?php

namespace App\Http;

class Response
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;

    public static function json(array $data = [], $httpCode = self::HTTP_OK): string
    {
        http_response_code($httpCode);

        return json_encode($data);
    }
}