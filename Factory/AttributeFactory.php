<?php

namespace Oander\Factory;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Oander\Models\Attribute;

class AttributeFactory
{

    private EntityManager $entityManager;
    
    public function __construct()
    {
        global $entityManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @return void
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function initCategories()
    {
        $attributes = [
            'size' => 'Méret',
            'resolution' => 'Felbontás',
            'brand' => 'Gyártó',
            'price' => 'Ár',
            'sale_price' => 'Akciós ár',
            'name' => 'Név',
            'description' => 'Leírás'
        ];

        foreach ($attributes as $code=>$name) {

            $qb = $this->entityManager->createQueryBuilder();

            $qb->select(array('a'))
                ->from(Attribute::class, 'a')
                ->where('a.code = :code');

            $qb->setParameter('code',$code);

            try {
                $eavAttribute = $qb->getQuery()->getSingleResult();
            } catch (NoResultException $exception) {
                $eavAttribute = false;
            }
            
            if(!$eavAttribute){
                $eavAttribute = new Attribute();
                $eavAttribute->setName($name);
                $eavAttribute->setCode($code);
                $this->entityManager->persist($eavAttribute);
                $this->entityManager->flush();
            }
        }
    }
}
