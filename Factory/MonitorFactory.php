<?php

namespace Oander\Factory;

use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Oander\Models\Monitor;

class MonitorFactory
{
    private EntityManager $entityManager;

    public function __construct()
    {
        global $entityManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @param $count
     * @return void
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createMonitors($count)
    {
        for ($x = 0; $x <= $count; $x++) {
            $faker = Factory::create();
            $monitor = new Monitor();
            $monitor->setName($faker->word);
            $monitor->setSize($faker->numberBetween(18, 40));
            $monitor->setResolution(sprintf("%s x %s",$faker->numberBetween(1000, 4000),$faker->numberBetween(1000, 4000)));
            $monitor->setBrand($faker->word);
            $monitor->setPrice($faker->numberBetween(20000, 200000));
            $monitor->setSalePrice($faker->numberBetween(20000, 200000));
            $monitor->setDescription($faker->text);
            $this->entityManager->persist($monitor);
            $this->entityManager->flush();
        }
    }
}
