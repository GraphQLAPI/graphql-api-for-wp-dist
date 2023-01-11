<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\Config\Definition\Builder;

/**
 * This class provides a fluent interface for building a node.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class NodeBuilder implements NodeParentInterface
{
    protected $parent;
    protected $nodeMapping;
    public function __construct()
    {
        $this->nodeMapping = ['variable' => VariableNodeDefinition::class, 'scalar' => ScalarNodeDefinition::class, 'boolean' => BooleanNodeDefinition::class, 'integer' => IntegerNodeDefinition::class, 'float' => FloatNodeDefinition::class, 'array' => ArrayNodeDefinition::class, 'enum' => EnumNodeDefinition::class];
    }
    /**
     * Set the parent node.
     *
     * @return $this
     * @param \Symfony\Component\Config\Definition\Builder\ParentNodeDefinitionInterface|null $parent
     */
    public function setParent($parent = null)
    {
        if (1 > \func_num_args()) {
            trigger_deprecation('symfony/form', '6.2', 'Calling "%s()" without any arguments is deprecated, pass null explicitly instead.', __METHOD__);
        }
        $this->parent = $parent;
        return $this;
    }
    /**
     * Creates a child array node.
     * @param string $name
     */
    public function arrayNode($name) : ArrayNodeDefinition
    {
        return $this->node($name, 'array');
    }
    /**
     * Creates a child scalar node.
     * @param string $name
     */
    public function scalarNode($name) : ScalarNodeDefinition
    {
        return $this->node($name, 'scalar');
    }
    /**
     * Creates a child Boolean node.
     * @param string $name
     */
    public function booleanNode($name) : BooleanNodeDefinition
    {
        return $this->node($name, 'boolean');
    }
    /**
     * Creates a child integer node.
     * @param string $name
     */
    public function integerNode($name) : IntegerNodeDefinition
    {
        return $this->node($name, 'integer');
    }
    /**
     * Creates a child float node.
     * @param string $name
     */
    public function floatNode($name) : FloatNodeDefinition
    {
        return $this->node($name, 'float');
    }
    /**
     * Creates a child EnumNode.
     * @param string $name
     */
    public function enumNode($name) : EnumNodeDefinition
    {
        return $this->node($name, 'enum');
    }
    /**
     * Creates a child variable node.
     * @param string $name
     */
    public function variableNode($name) : VariableNodeDefinition
    {
        return $this->node($name, 'variable');
    }
    /**
     * Returns the parent node.
     *
     * @return NodeDefinition&ParentNodeDefinitionInterface
     */
    public function end()
    {
        return $this->parent;
    }
    /**
     * Creates a child node.
     *
     * @throws \RuntimeException When the node type is not registered
     * @throws \RuntimeException When the node class is not found
     * @param string|null $name
     * @param string $type
     */
    public function node($name, $type) : NodeDefinition
    {
        $class = $this->getNodeClass($type);
        $node = new $class($name);
        $this->append($node);
        return $node;
    }
    /**
     * Appends a node definition.
     *
     * Usage:
     *
     *     $node = new ArrayNodeDefinition('name')
     *         ->children()
     *             ->scalarNode('foo')->end()
     *             ->scalarNode('baz')->end()
     *             ->append($this->getBarNodeDefinition())
     *         ->end()
     *     ;
     *
     * @return $this
     * @param \Symfony\Component\Config\Definition\Builder\NodeDefinition $node
     */
    public function append($node)
    {
        if ($node instanceof BuilderAwareInterface) {
            $builder = clone $this;
            $builder->setParent(null);
            $node->setBuilder($builder);
        }
        if (null !== $this->parent) {
            $this->parent->append($node);
            // Make this builder the node parent to allow for a fluid interface
            $node->setParent($this);
        }
        return $this;
    }
    /**
     * Adds or overrides a node Type.
     *
     * @param string $type  The name of the type
     * @param string $class The fully qualified name the node definition class
     *
     * @return $this
     */
    public function setNodeClass($type, $class)
    {
        $this->nodeMapping[\strtolower($type)] = $class;
        return $this;
    }
    /**
     * Returns the class name of the node definition.
     *
     * @throws \RuntimeException When the node type is not registered
     * @throws \RuntimeException When the node class is not found
     * @param string $type
     */
    protected function getNodeClass($type) : string
    {
        $type = \strtolower($type);
        if (!isset($this->nodeMapping[$type])) {
            throw new \RuntimeException(\sprintf('The node type "%s" is not registered.', $type));
        }
        $class = $this->nodeMapping[$type];
        if (!\class_exists($class)) {
            throw new \RuntimeException(\sprintf('The node class "%s" does not exist.', $class));
        }
        return $class;
    }
}
