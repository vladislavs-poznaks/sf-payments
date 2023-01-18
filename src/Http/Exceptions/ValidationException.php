<?php

namespace App\Http\Exceptions;

use App\Http\Request;
use Exception;

class ValidationException extends Exception
{
    public function __construct(public Request $request)
    {
        parent::__construct();
    }
}
