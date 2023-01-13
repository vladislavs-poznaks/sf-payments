<?php

namespace App\Http\Controllers;

use App\Http\Request;

class PaymentsController
{
    public function store()
    {
        $attributes = Request::getInstance()->getAll();

        return json_encode($attributes);
    }
}