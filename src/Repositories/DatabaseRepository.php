<?php

namespace App\Repositories;

use Doctrine\DBAL\DriverManager;

class DatabaseRepository
{
    protected array $connectionParameters = [];

    public function __construct()
    {
        $this->connectionParameters = [
            'dbname' => $_ENV['DB_DATABASE'],
            'user' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'host' => $_ENV['DB_HOST'],
            'driver' => 'pdo_mysql',
        ];
    }

    public function getConnection()
    {
        return DriverManager::getConnection($this->connectionParameters);
    }
}