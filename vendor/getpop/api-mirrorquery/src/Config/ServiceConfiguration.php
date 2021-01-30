<?php

declare (strict_types=1);
namespace PoP\APIMirrorQuery\Config;

use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;
class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;
    protected static function configure() : void
    {
        \PoP\ComponentModel\Container\ContainerBuilderUtils::injectServicesIntoService(\PoP\ComponentModel\DataStructure\DataStructureManagerInterface::class, 'PoP\\APIMirrorQuery\\DataStructureFormatters', 'add');
    }
}
