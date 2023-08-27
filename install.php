<?php

require 'vendor/autoload.php';

use Oander\Factory\AttributeFactory;
use Oander\Factory\EntityManagerFactory;
use Oander\Factory\MonitorFactory;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$emFactory = new EntityManagerFactory();
$entityManager = $emFactory->getEntityManager();

$process = new Process(['php', 'vendor/bin/doctrine-migrations','migrate', '-n']);
$process->run();

if (!$process->isSuccessful()) {
    throw new ProcessFailedException($process);
}

echo $process->getOutput();

$attributeFactory = new AttributeFactory();
$attributeFactory->initCategories();
echo "Categories initialized!\n";

$monitoryFactory = new MonitorFactory();
$monitoryFactory->createMonitors(50);
echo "Monitors seeded!\n";