<?php

declare (strict_types=1);
namespace PoP\Root\Container\Loader;

use PrefixedByPoP\Symfony\Component\Config\FileLocatorInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
class ForceAutoconfigureYamlFileLoader extends YamlFileLoader
{
    /**
     * @var bool
     */
    protected $autoconfigure = \true;
    public function __construct(ContainerBuilder $container, FileLocatorInterface $locator, bool $autoconfigure = \true)
    {
        $this->autoconfigure = $autoconfigure;
        parent::__construct($container, $locator);
    }
    /**
     * Override the Symfony class, to always inject the
     * "autoconfigure" property
     */
    protected function loadFile($file)
    {
        $content = parent::loadFile($file);
        $content['services']['_defaults']['autoconfigure'] = $this->autoconfigure;
        return $content;
    }
}
