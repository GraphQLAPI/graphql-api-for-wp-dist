<?php

declare (strict_types=1);
namespace PrefixedByPoP;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return function (\PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $configurator) {
    $services = $configurator->services()->defaults()->public()->autowire();
    $services->load('GraphQLByPoP\\GraphQLServer\\ConditionalOnEnvironment\\RemoveIfNull\\SchemaServices\\', '../../../src/ConditionalOnEnvironment/RemoveIfNull/SchemaServices/*');
};
