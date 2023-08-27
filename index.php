<?php

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$emFactory = new \Oander\Factory\EntityManagerFactory();

$entityManager = $emFactory->getEntityManager();
$config = $entityManager->getConfiguration();
$config->addCustomNumericFunction('INT', \Oander\Query\CastAsInteger::class);

$monitorController = new Oander\Controllers\MonitorController();
echo $monitorController->index();