<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\Model;

use DawBed\ContextBundle\Entity\AbstractContext;

class CreateModel
{
    private $entity;

    function __construct(AbstractContext $status)
    {
        $this->setEntity($status);
    }

    public function setEntity(AbstractContext $status) : void
    {
        $this->entity = $status;
    }

    public function getEntity(): AbstractContext
    {
        return $this->entity;
    }
}