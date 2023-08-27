<?php
namespace Oander\Factory;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class EntityManagerFactory
{
    /**
     * @return EntityManager
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\ORM\Exception\MissingMappingDriverImplementation
     */
    public function getEntityManager(){
        
        $paths = ['Models'];
        $isDevMode = false;

        $dbParams = [
            'dbname' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'host' => $_ENV['DB_HOST'],
            'driver' => 'pdo_mysql',
        ];

        $config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
        $connection = DriverManager::getConnection($dbParams, $config);
        $entityManager = new EntityManager($connection, $config);
        
        return $entityManager;
    }
}
