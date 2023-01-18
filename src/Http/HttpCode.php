<?php

namespace App\Http;

enum HttpCode: int
{
    case OK = 200;

    case CREATED = 201;

    case BAD_REQUEST = 400;
    case CONFLICT = 409;
    case UNPROCESSABLE_ENTITY = 422;

    case INTERNAL_ERROR = 500;
}