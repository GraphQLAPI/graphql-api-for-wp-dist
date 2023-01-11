<?php

declare (strict_types=1);
namespace PoP\Root\Container\Loader;

use PrefixedByPoP\Symfony\Component\Config\FileLocatorInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
/**
 * Override the Symfony class, to:
 *
 * - always inject the "autoconfigure" property
 * - add the required tag "container.ignore_attributes" to avoid PHP 8's attributes
 */
class SchemaServiceYamlFileLoader extends YamlFileLoader
{
    use \PoP\Root\Container\Loader\ServiceYamlFileLoaderTrait;
    /**
     * @var bool
     */
    protected $autoconfigure;
    public function __construct(ContainerBuilder $container, FileLocatorInterface $locator, bool $autoconfigure)
    {
        $this->autoconfigure = $autoconfigure;
        parent::__construct($container, $locator);
    }
    /**
     * @return mixed[]|null
     * @param string $file
     */
    protected function loadFile($file) : ?array
    {
        $content = parent::loadFile($file);
        if ($content === null) {
            return null;
        }
        $content['services']['_defaults']['autoconfigure'] = $this->autoconfigure;
        return $this->customizeYamlFileDefinition($content);
    }
}
