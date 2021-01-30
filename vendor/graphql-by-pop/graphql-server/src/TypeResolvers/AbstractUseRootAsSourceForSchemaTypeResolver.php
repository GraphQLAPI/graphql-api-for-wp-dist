<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
abstract class AbstractUseRootAsSourceForSchemaTypeResolver extends \PoP\ComponentModel\TypeResolvers\AbstractTypeResolver
{
    protected function getTypeResolverClassToCalculateSchema() : string
    {
        return \PoP\Engine\TypeResolvers\RootTypeResolver::class;
    }
    protected abstract function isFieldNameConditionSatisfiedForSchema(\PoP\ComponentModel\FieldResolvers\FieldResolverInterface $fieldResolver, string $fieldName) : bool;
    protected function isFieldNameResolvedByFieldResolver(\PoP\ComponentModel\FieldResolvers\FieldResolverInterface $fieldResolver, string $fieldName, array $fieldInterfaceResolverClasses) : bool
    {
        if (!$this->isFieldNameConditionSatisfiedForSchema($fieldResolver, $fieldName)) {
            return \false;
        }
        return parent::isFieldNameResolvedByFieldResolver($fieldResolver, $fieldName, $fieldInterfaceResolverClasses);
    }
}
