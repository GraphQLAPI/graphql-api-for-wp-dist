<?php

declare (strict_types=1);
namespace PoPAPI\API\Schema;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class SchemaDefinitionHelpers
{
    /**
     * Replace the typeResolver with the typeName (maybe namespaced) and kind
     * @param array<string,mixed> $schemaDefinition
     */
    public static function replaceTypeResolverWithTypeProperties(&$schemaDefinition) : void
    {
        $typeResolver = $schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPE_RESOLVER];
        $schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPE_NAME] = $typeResolver->getMaybeNamespacedTypeName();
        $typeKind = null;
        if ($typeResolver instanceof ObjectTypeResolverInterface) {
            $typeKind = \PoPAPI\API\Schema\TypeKinds::OBJECT;
        } elseif ($typeResolver instanceof InterfaceTypeResolverInterface) {
            $typeKind = \PoPAPI\API\Schema\TypeKinds::INTERFACE;
        } elseif ($typeResolver instanceof UnionTypeResolverInterface) {
            $typeKind = \PoPAPI\API\Schema\TypeKinds::UNION;
        } elseif ($typeResolver instanceof ScalarTypeResolverInterface) {
            $typeKind = \PoPAPI\API\Schema\TypeKinds::SCALAR;
        } elseif ($typeResolver instanceof EnumTypeResolverInterface) {
            $typeKind = \PoPAPI\API\Schema\TypeKinds::ENUM;
        } elseif ($typeResolver instanceof InputObjectTypeResolverInterface) {
            $typeKind = \PoPAPI\API\Schema\TypeKinds::INPUT_OBJECT;
        }
        $schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPE_KIND] = $typeKind;
        unset($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPE_RESOLVER]);
    }
}
