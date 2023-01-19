#!/usr/bin/env php
<?php

use App\Commands\PaymentsImportCommand;
use App\Commands\PaymentsReportCommand;
use App\Repositories\DatabaseRepository;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Symfony\Component\Console\Application;

$container = require __DIR__ . '/bootstrap.php';

$repository = new DatabaseRepository();

$entityManager = $repository->getEntityManager();

$commands = [
    $container->get(PaymentsReportCommand::class),
    $container->get(PaymentsImportCommand::class),
];

$application = new Application($_ENV['APP_NAME'], $_ENV['APP_VERSION']);

ConsoleRunner::addCommands($application, new SingleManagerProvider($entityManager));

$application->addCommands($commands);

$application->run();
