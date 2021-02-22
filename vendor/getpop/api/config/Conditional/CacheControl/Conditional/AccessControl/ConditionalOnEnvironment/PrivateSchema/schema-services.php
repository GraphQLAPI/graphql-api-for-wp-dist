<?php

declare (strict_types=1);
namespace PrefixedByPoP;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return function (\PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $configurator) {
    $services = $configurator->services()->defaults()->public()->autowire();
    $services->load('PoP\\API\\Conditional\\CacheControl\\Conditional\\AccessControl\\ConditionalOnEnvironment\\PrivateSchema\\SchemaServices\\', '../../../../../../../src/Conditional/CacheControl/Conditional/AccessControl/ConditionalOnEnvironment/PrivateSchema/SchemaServices/*');
};
