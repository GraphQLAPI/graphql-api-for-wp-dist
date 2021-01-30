<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;
abstract class AbstractQueryableSchemaFieldInterfaceResolver extends \PoP\ComponentModel\FieldInterfaceResolvers\AbstractSchemaFieldInterfaceResolver
{
    use QueryableFieldResolverTrait;
}
