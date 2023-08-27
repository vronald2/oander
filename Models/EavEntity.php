<?php

namespace Oander\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\NoResultException;

#[Entity]
#[Table(name: 'monitors')]
class EavEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;
    #[ORM\Column(length: 140)]
    private $name;

    #[ORM\OneToMany(targetEntity: AttributeValue::class, mappedBy: 'eavEntity', cascade: ['persist', 'remove'])]
    private Collection $attributeValues;

    private $entityManager;
    
    public function __construct() {
        global $entityManager;
        $this->attributeValues = new ArrayCollection();
        $this->entityManager = $entityManager;
    }
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection
     */
    public function getAttributeValues(): Collection
    {
        return $this->attributeValues;
    }

    /**
     * @param Collection $attributeValues
     */
    public function setAttributeValues(Collection $attributeValues): void
    {
        $this->attributeValues = $attributeValues;
    }
    
    public function addAttributeValue(AttributeValue $attributeValue){
        $attributeValue->setEavEntity($this);
        $this->attributeValues[] = $attributeValue;
    }

    /**
     * Magic method call for setter and getter
     *
     * @param $methodName
     * @param $params
     *
     * @return mixed
     */
    public function __call($methodName, $params = null)
    {

        global $entityManager;
        $this->entityManager = $entityManager;
        
        $methodPrefix = substr($methodName, 0, 3);
        
        $attr = self::camelCaseToSnakeCase(substr($methodName, 3));
        
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select(array('a'))
            ->from(Attribute::class, 'a')
            ->where('a.code = :code');

        $qb->setParameter('code',$attr);

        try {
            $attribute = $qb->getQuery()->getSingleResult();
        }catch (\Exception $exception){
            $attribute = new Attribute();
        }
        
        if ($methodPrefix == 'set' && count($params) == 1) {
            // Get the parameter value
            $value = $params[0];
            
            $qb = $this->entityManager->createQueryBuilder();

            $qb->select(array('av'))
                ->from(AttributeValue::class, 'av')
                ->where('av.attribute = :attribute_id')
                ->andWhere('av.eavEntity  = :entity_id');

            $qb->setParameters(['attribute_id' =>$attribute->getId(),'entity_id'=>$this->getId()]);
            
            try{
                $attributeValue = $qb->getQuery()->getSingleResult();
            }catch (NoResultException $exception){
                $attributeValue = new AttributeValue();
                $attributeValue->setEavEntity($this);
                $attributeValue->setAttribute($attribute);
                $attributeValue->setValue($value);
                
                $this->addAttributeValue($attributeValue);
                
                $entityManager->persist($attributeValue);
            }
        } elseif ($methodPrefix == 'get') {
            
            $qb = $this->entityManager->createQueryBuilder();

            $qb->select(array('av'))
                ->from(AttributeValue::class, 'av')
                ->where('av.attribute = :attribute_id')
                ->andWhere('av.eavEntity  = :entity_id');
            
            $qb->setParameters(['attribute_id' =>$attribute->getId(),'entity_id'=>$this->getId()]);
            $attributeValue = $qb->getQuery()->getSingleResult();
            return $attributeValue->getValue();
           
        }
    }
    
    function camelCaseToSnakeCase($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }
}