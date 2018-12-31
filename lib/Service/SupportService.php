<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\Service;

use DawBed\PHPContext\ContextInterface;

class SupportService
{
    private $types = [];

    function __construct(array $types)
    {
        $this->types = $types;
    }

    public function isOrThrow(ContextInterface $context): bool
    {
        if (!in_array($context->getType(), $this->types)) {
            throw new \Exception(sprintf('%s is not supported. Before use declare it in configuration file', $context->getType()));
        }
        return true;
    }
}