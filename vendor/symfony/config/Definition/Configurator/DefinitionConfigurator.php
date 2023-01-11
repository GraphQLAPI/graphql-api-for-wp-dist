<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\Config\Definition\Configurator;

use PrefixedByPoP\Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use PrefixedByPoP\Symfony\Component\Config\Definition\Builder\NodeDefinition;
use PrefixedByPoP\Symfony\Component\Config\Definition\Builder\TreeBuilder;
use PrefixedByPoP\Symfony\Component\Config\Definition\Loader\DefinitionFileLoader;
/**
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class DefinitionConfigurator
{
    /**
     * @var \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    private $treeBuilder;
    /**
     * @var \Symfony\Component\Config\Definition\Loader\DefinitionFileLoader
     */
    private $loader;
    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $file;
    public function __construct(TreeBuilder $treeBuilder, DefinitionFileLoader $loader, string $path, string $file)
    {
        $this->treeBuilder = $treeBuilder;
        $this->loader = $loader;
        $this->path = $path;
        $this->file = $file;
    }
    /**
     * @param string $resource
     * @param string|null $type
     * @param bool $ignoreErrors
     */
    public function import($resource, $type = null, $ignoreErrors = \false) : void
    {
        $this->loader->setCurrentDir(\dirname($this->path));
        $this->loader->import($resource, $type, $ignoreErrors, $this->file);
    }
    /**
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition|\Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     */
    public function rootNode()
    {
        return $this->treeBuilder->getRootNode();
    }
    /**
     * @param string $separator
     */
    public function setPathSeparator($separator) : void
    {
        $this->treeBuilder->setPathSeparator($separator);
    }
}
