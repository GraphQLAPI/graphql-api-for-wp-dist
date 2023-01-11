<?php

declare (strict_types=1);
namespace GraphQLAPI\ExternalDependencyWrappers\Composer\Semver;

use PrefixedByPoP\Composer\Semver\Semver;
/**
 * Wrapper for Composer\Semver\Semver.
 *
 * These methods are accessed static, instead of via a service,
 * since they are referenced in ExtensionManager, before
 * the container service has been initialized.
 */
class SemverWrapper
{
    /**
     * Determine if given version satisfies given constraints.
     * @param string $version
     * @param string $constraints
     */
    public static function satisfies($version, $constraints) : bool
    {
        return Semver::satisfies($version, $constraints);
    }
}
