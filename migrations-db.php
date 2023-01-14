<?php

use App\Repositories\DatabaseRepository;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$repository = new DatabaseRepository();

return $repository->getConnection();
