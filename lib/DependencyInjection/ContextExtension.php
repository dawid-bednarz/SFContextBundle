<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\DependencyInjection;

use DawBed\ContextBundle\Entity\Context;
use DawBed\ContextBundle\EventListener\ContextDiscriminatorListener;
use DawBed\ContextBundle\Provider;
use DawBed\ContextBundle\Service\EntityService;
use DawBed\ContextBundle\Service\SupportService;
use DawBed\PHPClassProvider\ClassProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class ContextExtension extends Extension implements PrependExtensionInterface
{
    const ALIAS = 'dawbed_context_bundle';

    public function prepend(ContainerBuilder $container): void
    {
        $container->setParameter('bundle_dir', dirname(__DIR__));
        $loader = $this->prepareLoader($container);
        $loader->load('services.yaml');
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $contextTypes = $this->getTypes($configs);
        $configs = $this->processConfiguration($configuration, $configs);
        $this->prepareLoader($container);
        $this->prepareProvider($contextTypes, $container);
        $this->prepareEntityProvider($configs['entities'], $container);
        $this->prepareDiscriminatorMapping($configs['entities'], $configs['discriminator_map'], $container);
    }

    public function getAlias(): string
    {
        return self::ALIAS;
    }

    private function prepareProvider(array $types, ContainerBuilder $containerBuilder)
    {
        $containerBuilder->setDefinition(Provider::class, new Definition(Provider::class, [
            $types,
            new Reference(EntityManagerInterface::class)
        ]));
    }

    private function prepareLoader(ContainerBuilder $containerBuilder): YamlFileLoader
    {
        return new YamlFileLoader($containerBuilder, new FileLocator(dirname(__DIR__) . '/Resources/config'));
    }

    private function prepareDiscriminatorMapping(array $entities, array $mapping, ContainerBuilder $container): void
    {
        $mapping[$entities[Context::class]] = $entities[Context::class];
        $listener = new Definition(ContextDiscriminatorListener::class, [$mapping]);
        $listener->setTags(['doctrine.event_listener' => [['event' => 'loadClassMetadata']]]);
        $container->setDefinition(ContextDiscriminatorListener::class, $listener);
    }

    private function prepareEntityProvider(array $entities, ContainerBuilder $container): void
    {
        foreach ($entities as $name => $class) {
            ClassProvider::add($name, $class);
        }
    }

    private function getTypes(array $configs): array
    {
        $types = [];

        foreach ($configs as $config) {
            if (array_key_exists(Configuration::NODE_TYPES, $config)) {
                foreach ($config[Configuration::NODE_TYPES] as $type => $data) {
                    if (array_key_exists($type, $types)) {
                        throw new \Exception(sprintf('Duplicate Type "%s"', $value));
                    }
                    $types[$type] = $data;
                }
            }
        }
        return $types;
    }

}