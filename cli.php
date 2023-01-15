#!/usr/bin/env php
<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

$container = require __DIR__ . '/bootstrap.php';

$repository = new \App\Repositories\DatabaseRepository();

$entityManager = $repository->getEntityManager();

$commands = [
    new \App\Commands\PaymentsReportCommand(),
    new \App\Commands\PaymentsImportCommand(),
];

$application = new \Symfony\Component\Console\Application($_ENV['APP_NAME'], $_ENV['APP_VERSION']);

ConsoleRunner::addCommands($application, new SingleManagerProvider($entityManager));

$application->addCommands($commands);

$application->run();
