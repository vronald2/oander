<?php
namespace Oander\Models;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;

#[Entity]
#[Table(name: 'attribute_values')]
class AttributeValue
{

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;
    
    #[ORM\ManyToOne(targetEntity: Attribute::class)]
    #[ORM\JoinColumn(name: 'attribute_id', referencedColumnName: 'id')]
    private Attribute|null $attribute = null;
    
    #[ORM\Column(type: 'text')]
    private $value;

    #[ORM\ManyToOne(targetEntity: EavEntity::class, inversedBy: 'attributeValues')]
    #[ORM\JoinColumn(name: 'entity_id', referencedColumnName: 'id')]
    private EavEntity|null $eavEntity = null;
    
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
    public function getAttributeId()
    {
        return $this->attribute_id;
    }

    /**
     * @param mixed $attribute_id
     */
    public function setAttributeId($attribute_id): void
    {
        $this->attribute_id = $attribute_id;
    }


    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return EavEntity|null
     */
    public function getEavEntity(): ?EavEntity
    {
        return $this->eavEntity;
    }

    /**
     * @param EavEntity|null $eavEntity
     */
    public function setEavEntity(?EavEntity $eavEntity): void
    {
        $this->eavEntity = $eavEntity;
    }

    
    
}