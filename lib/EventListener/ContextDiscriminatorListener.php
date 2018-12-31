<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\EventListener;

use DawBed\PHPContext\Context;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\DiscriminatorMap;

class ContextDiscriminatorListener
{
    private $mapping;

    function __construct(array $mapping = [])
    {
        $this->mapping = $mapping;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        $metadata = $event->getClassMetadata();
        $class = $metadata->getReflectionClass();

        if ($class === null) {
            $class = new \ReflectionClass($metadata->getName());
        }

        if ($class->getName() == Context::class) {
            $reader = new AnnotationReader();
            $discriminatorMap = [];
            if (null !== $discriminatorMapAnnotation = $reader->getClassAnnotation($class, DiscriminatorMap::class)) {
                $discriminatorMap = $discriminatorMapAnnotation->value;
            }
            $discriminatorMap = array_merge($discriminatorMap, $this->mapping);
            $metadata->setDiscriminatorMap($discriminatorMap);
        }
    }
}