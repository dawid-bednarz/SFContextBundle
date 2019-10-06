<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\Entity;

class Context
{
    protected $type;
    protected $name;
    protected $id;
    protected $discriminator;
    protected $groups;

    public function getDiscriminator()
    {
        return $this->discriminator;
    }

    public function setDiscriminator($discriminator)
    {
        $this->discriminator = $discriminator;
        return $this;
    }

    public function getId():?int
    {
        return $this->id;
    }

    public function setId(int $id): Context
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): Context
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Context
    {
        $this->type = $type;
        return $this;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($groups)
    {
        $this->groups = $groups;
        return $this;
    }
}