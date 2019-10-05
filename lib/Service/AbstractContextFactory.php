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

        if (array_key_exists($key, $factory)) {
            throw new AbstractFactoryException ('Unknown Factory');
        }
        $model = new CreateModel($factory[$key]());

        $this->getCreateService()->make($model);

        return $model->getEntity();
    }

    protected abstract function getFactories(): array;

    protected abstract function getCreateService(): CreateServiceInterface;
}

class AbstractFactoryException extends \Exception
{
}