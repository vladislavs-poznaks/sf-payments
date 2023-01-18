<?php

namespace App\Loggers;

use Carbon\Carbon;
use Exception;

class FileLogger implements Logger
{
    public static function error(Exception $exception, string $prefix = ''): void
    {
        $date = Carbon::now()->toDateString();

        $path = __DIR__ . '/../../logs/';

        if (!file_exists($path)) {
            mkdir($path);
        }

        $filename = ($prefix ? $prefix . '-' . $date : $date) . '.log';

        $log = json_encode([
            'message' => $exception->getMessage(),
            'trace' => $exception->getTrace()
        ]) . PHP_EOL;

        file_put_contents($path . $filename, $log, FILE_APPEND);
    }
}
