<?php

use Doctrine\DBAL\Types\Type;

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

Type::addType(\Ramsey\Uuid\Doctrine\UuidType::NAME, \Ramsey\Uuid\Doctrine\UuidType::class);
Type::addType(\App\Types\CarbonType::NAME, \App\Types\CarbonType::class);
Type::addType(\App\Types\AmountType::NAME, \App\Types\AmountType::class);
Type::addType(\App\Types\LoanNumberType::NAME, \App\Types\LoanNumberType::class);
