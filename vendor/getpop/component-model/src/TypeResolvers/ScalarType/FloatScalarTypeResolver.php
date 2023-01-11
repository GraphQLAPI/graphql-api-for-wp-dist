<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers\ScalarType;

/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 */
class FloatScalarTypeResolver extends \PoP\ComponentModel\TypeResolvers\ScalarType\AbstractFloatScalarTypeResolver
{
    use \PoP\ComponentModel\TypeResolvers\ScalarType\BuiltInScalarTypeResolverTrait;
    public function getTypeName() : string
    {
        return 'Float';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('The Float scalar type represents float numbers.', 'component-model');
    }
}
