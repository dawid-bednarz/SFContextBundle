<?php
/**
 *  * Created by PhpStorm.
 * User: Dawid Bednarz( dawid@bednarz.pro )
 */
declare(strict_types=1);

namespace DawBed\ContextBundle\DependencyInjection;

use DawBed\ComponentBundle\Configuration\Entity;
use DawBed\PHPContext\Context;
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
        $entity->new('context', Context::class);
        $entity->end();

        $this->contextTypes($rootNode
            ->children()
            ->arrayNode(self::NODE_TYPES))
            ->end();

        $this->discriminatorMap($rootNode
            ->children()
            ->arrayNode(self::NODE_DISCRIMINATOR_MAP))
            ->end();

        return $treeBuilder;
    }

    private function contextTypes(ArrayNodeDefinition $contextTypes): ArrayNodeDefinition
    {
        $contextTypes
            ->scalarPrototype()
            ->validate()
            ->ifTrue(function ($v) {
                return !is_integer($v);
            })->thenInvalid('key status must be an integer type');

        return $contextTypes;
    }

    private function discriminatorMap(ArrayNodeDefinition $discriminatorMap): ArrayNodeDefinition
    {
        $discriminatorMap
            ->scalarPrototype()
            ->validate()
            ->ifTrue(function ($v) {
                return !is_string($v);
            })->thenInvalid('key status must be a string type');

        return $discriminatorMap;
    }

}