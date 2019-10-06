<?php

/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */

namespace DawBed\ContextBundle\Entity;

class AbstractGroup
{
    protected $id;
    protected $name;
    protected $context;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): AbstractGroup
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): AbstractGroup
    {
        $this->name = $name;
        return $this;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function setContext(Context $context): AbstractGroup
    {
        $this->context = $context;
        return $this;
    }
}