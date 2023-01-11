<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers;

use PoP\Root\Services\BasicServiceTrait;
abstract class AbstractFieldResolver implements \PoP\ComponentModel\FieldResolvers\FieldResolverInterface
{
    use BasicServiceTrait;
    /**
     * @return string[]
     */
    public function getSensitiveFieldNames() : array
    {
        return [];
    }
}
