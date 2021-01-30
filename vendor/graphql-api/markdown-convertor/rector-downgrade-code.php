<?php

declare (strict_types=1);
namespace PrefixedByPoP;

use PrefixedByPoP\Rector\Core\Configuration\Option;
use PrefixedByPoP\Rector\Core\ValueObject\PhpVersion;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use PrefixedByPoP\Rector\Set\ValueObject\DowngradeSetList;
return static function (\PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
    // here we can define, what sets of rules will be applied
    $parameters->set(\PrefixedByPoP\Rector\Core\Configuration\Option::SETS, [
        // @todo Uncomment when PHP 8.0 released
        // DowngradeSetList::PHP_80,
        \PrefixedByPoP\Rector\Set\ValueObject\DowngradeSetList::PHP_74,
        \PrefixedByPoP\Rector\Set\ValueObject\DowngradeSetList::PHP_73,
        \PrefixedByPoP\Rector\Set\ValueObject\DowngradeSetList::PHP_72,
    ]);
    // is your PHP version different from the one your refactor to? [default: your PHP version]
    $parameters->set(\PrefixedByPoP\Rector\Core\Configuration\Option::PHP_VERSION_FEATURES, \PrefixedByPoP\Rector\Core\ValueObject\PhpVersion::PHP_71);
};
