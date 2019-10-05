<?php
/**
 *  * Dawid Bednarz( dawid@bednarz.pro )
 * Read README.md file for more information and licence uses
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\DependencyInjection\Compiler;

use DawBed\ContextBundle\Entity\AbstractContext;
use DawBed\PHPClassProvider\ClassProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineResolveTargetEntityPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');
        $definition->addMethodCall('addResolveTargetEntity', [
            AbstractContext::class,
            ClassProvider::get(AbstractContext::class),
            [],
        ]);
        $definition->addTag('doctrine.event_subscriber', ['connection' => 'default']);
    }
}