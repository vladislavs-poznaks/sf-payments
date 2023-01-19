<?php

namespace App\Loggers;

use Exception;

interface Logger
{
    public static function error(Exception $exception, string $prefix = ''): void;
}
