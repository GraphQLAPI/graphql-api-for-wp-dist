<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;
class SchemaDefinitionService implements \PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface
{
    public function getInterfaceSchemaKey(FieldInterfaceResolverInterface $interfaceResolver) : string
    {
        // By default, use the type name
        return $interfaceResolver->getMaybeNamespacedInterfaceName();
    }
    public function getTypeSchemaKey(TypeResolverInterface $typeResolver) : string
    {
        // By default, use the type name
        return $typeResolver->getMaybeNamespacedTypeName();
    }
    /**
     * The `mixed` type is a wildcard type,
     * representing *any* type (string, int, bool, etc)
     */
    public function getDefaultType() : string
    {
        return \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ANY_SCALAR;
    }
}
