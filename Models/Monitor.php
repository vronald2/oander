<?php

namespace Oander\Models;


use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'monitors')]
class Monitor extends EavEntity
{
    
}