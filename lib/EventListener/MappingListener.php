<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\EventListener;

use DawBed\ContextBundle\Entity\AbstractGroup;
use DawBed\ContextBundle\Entity\Context;
use DawBed\PHPClassProvider\ClassProvider;
use DawBed\UserBundle\Entity\AbstractUser;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class MappingListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if ($classMetadata->getName() !== Context::class) {
            return;
        }
        $classMetadata->mapOneToMany(array(
            'targetEntity' => ClassProvider::get(AbstractGroup::class),
            'fieldName' => 'groups',
            'cascade' => ['persist'],
            'mappedBy' => 'context'
        ));
    }
}