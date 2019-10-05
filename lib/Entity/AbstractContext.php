<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\Entity;

abstract class AbstractContext implements ContextInterface
{
    protected $type;
    protected $name;
    protected $id;
    protected $discriminator;

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

    public function setName(string $name): ContextInterface
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): ContextInterface
    {
        $this->type = $type;
        return $this;
    }
}
