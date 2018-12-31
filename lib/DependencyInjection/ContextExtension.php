<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\DependencyInjection;

use DawBed\ContextBundle\EventListener\ContextDiscriminatorListener;
use DawBed\ContextBundle\Service\EntityService;
use DawBed\ContextBundle\Service\SupportService;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ContextExtension extends Extension implements PrependExtensionInterface
{
    const ALIAS = 'dawbed_context_bundle';

    public function prepend(ContainerBuilder $container): void
    {
        $loader = $this->prepareLoader($container);
        $loader->load('services.yaml');
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $contextTypes = $this->getTypes($configs);
        $configs = $this->processConfiguration($configuration, $configs);
        $this->prepareLoader($container);
        $this->prepareSupportService($contextTypes, $container);
        $this->prepareEntityService($configs['entities'], $container);
        $this->prepareDiscriminatorMapping($configs['entities'], $configs['discriminator_map'], $container);
    }

    public function getAlias(): string
    {
        return self::ALIAS;
    }

    private function prepareLoader(ContainerBuilder $containerBuilder): YamlFileLoader
    {
        return new YamlFileLoader($containerBuilder, new FileLocator(dirname(__DIR__) . '/Resources/config'));
    }

    private function prepareDiscriminatorMapping(array $entities, array $mapping, ContainerBuilder $container): void
    {
        $mapping[$entities['context']] = $entities['context'];
        $listener = new Definition(ContextDiscriminatorListener::class, [$mapping]);
        $listener->setTags(['doctrine.event_listener' => [['event' => 'loadClassMetadata']]]);
        $container->setDefinition(ContextDiscriminatorListener::class, $listener);
    }

    private function prepareEntityService(array $entities, ContainerBuilder $container): void
    {
        $container->setDefinition(EntityService::class, new Definition(EntityService::class, [
            [
                'Context' => $entities['context'],
            ]
        ]));
    }
    private function prepareSupportService(array $types, ContainerBuilder $container): void
    {
        $container->setDefinition(SupportService::class, new Definition(SupportService::class, [
            $types
        ]));
    }

    private function getTypes(array $configs): array
    {
        $types = [];

        foreach ($configs as $config) {
            if (array_key_exists(Configuration::NODE_TYPES, $config)) {
                foreach ($config[Configuration::NODE_TYPES] as $value) {
                    if (in_array($value, $types)) {
                        throw new \Exception(sprintf('Duplicate Type "%s"', $value));
                    }
                    $types[] = $value;
                }
            }
        }
        return $types;
    }

}