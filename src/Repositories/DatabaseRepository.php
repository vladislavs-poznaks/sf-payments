<?php

namespace App\Repositories;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class DatabaseRepository
{
    protected EntityManager $entityManager;

    protected static ?Connection $connection = null;

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

        $this->entityManager = new EntityManager(
            $this->getConnection(),
            ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/../Models'])
        );
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    public function getConnection()
    {
        return DriverManager::getConnection($this->connectionParameters);
    }
}