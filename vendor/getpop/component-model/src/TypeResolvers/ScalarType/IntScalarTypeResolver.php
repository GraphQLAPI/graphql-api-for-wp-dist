<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers\ScalarType;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
class IntScalarTypeResolver extends \PoP\ComponentModel\TypeResolvers\ScalarType\AbstractIntScalarTypeResolver
{
    use \PoP\ComponentModel\TypeResolvers\ScalarType\BuiltInScalarTypeResolverTrait;
    public function getTypeName() : string
    {
        return 'Int';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('The Int scalar type represents non-fractional signed whole numeric values.', 'component-model');
    }
}
