<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\DependencyInjection;

use DawBed\ComponentBundle\Configuration\Entity;
use DawBed\ContextBundle\Entity\AbstractGroup;
use DawBed\ContextBundle\Entity\Context;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const NODE_TYPES = 'types';
    public const NODE_DISCRIMINATOR_MAP = 'discriminator_map';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(ContextExtension::ALIAS);

        $entity = new Entity($rootNode);

        $entity->new(Context::class, Context::class)
            ->new(AbstractGroup::class, AbstractGroup::class)
            ->end();

        $this->contextTypes($rootNode);
        $this->discriminatorMap($rootNode);

        return $treeBuilder;
    }

    private function contextTypes(ArrayNodeDefinition $contextTypes): ArrayNodeDefinition
    {
        $contextTypes
            ->children()
            ->arrayNode(self::NODE_TYPES)
            ->arrayPrototype()
            ->children()
            ->scalarNode('name')
            ->isRequired()
            ->end()
            ->scalarNode('description')
            ->end()
            ->arrayNode('groups')
            ->scalarPrototype()
            ->end()
            ->end();

        return $contextTypes;
    }

    private function discriminatorMap(ArrayNodeDefinition $discriminatorMap): ArrayNodeDefinition
    {
        $discriminatorMap
            ->children()
            ->arrayNode(self::NODE_DISCRIMINATOR_MAP)
            ->scalarPrototype()
            ->validate()
            ->ifTrue(function ($v) {
                return !is_string($v);
            })->thenInvalid('key status must be a string type')
            ->end();

        return $discriminatorMap;
    }

}