<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\Service;

use DawBed\PHPContext\ContextInterface;
use DawBed\PHPContext\Model\CreateModel;

abstract class AbstractContextFactory
{
    public function build(int $key): ContextInterface
    {
        $factory = $this->getFactories();

        if (!$factory->offsetExists($key)) {
            throw new AbstractFactoryException ('Unknown Factory');
        }
        $model = new CreateModel($factory[$key]());

        $this->getCreateService()->make($model);

        return $model->getEntity();
    }

    protected abstract function getFactories(): FactoryCollection;

    protected abstract function getCreateService(): CreateServiceInterface;
}

class FactoryCollection implements \ArrayAccess
{
    private $factories;

    public function append(int $type, \Closure $factory): FactoryCollection
    {
        $this->factories[$type] = $factory;

        return $this;
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->factories);
    }

    public function offsetGet($offset): \Closure
    {
        return $this->factories[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->append($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->factories[$offset]);
    }
}

class AbstractFactoryException extends \Exception
{
}