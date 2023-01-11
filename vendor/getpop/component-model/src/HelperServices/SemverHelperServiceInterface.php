<?php

declare (strict_types=1);
namespace PoP\ComponentModel\HelperServices;

interface SemverHelperServiceInterface
{
    /**
     * Determine if given version satisfies given constraints.
     * @param string $version
     * @param string $constraints
     */
    public function satisfies($version, $constraints) : bool;
}
