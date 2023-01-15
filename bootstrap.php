<?php

use DI\ContainerBuilder;
use Doctrine\DBAL\Types\Type;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Doctrine ORM mapping types
Type::addType(\Ramsey\Uuid\Doctrine\UuidType::NAME, \Ramsey\Uuid\Doctrine\UuidType::class);
Type::addType(\App\Types\CarbonType::NAME, \App\Types\CarbonType::class);
Type::addType(\App\Types\AmountType::NAME, \App\Types\AmountType::class);
Type::addType(\App\Types\LoanNumberType::NAME, \App\Types\LoanNumberType::class);

// Custom validation
Valitron\Validator::addRule('paymentDateFormat', function($field, $value, array $params, array $fields) {
    try {
        \Carbon\Carbon::createFromFormat('c', $value);
        return true;
    } catch (\Carbon\Exceptions\InvalidFormatException) {
        return false;
    }
}, "Incorrect {field} format");

// IoC container
$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions(__DIR__ . '/app.php');
$container = $containerBuilder->build();

return $container;
