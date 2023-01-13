<?php

namespace App\Http\Controllers;

use App\Http\Request;
use App\Http\Response;

class PaymentsController
{
    public function store()
    {
        $attributes = Request::getInstance()->getAll();

        return Response::json($attributes, Response::HTTP_CREATED);
    }
}