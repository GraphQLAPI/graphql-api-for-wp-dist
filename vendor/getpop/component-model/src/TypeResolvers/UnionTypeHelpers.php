<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use function substr;
use function explode;
class UnionTypeHelpers
{
    /**
     * If the type data resolver starts with "*" then it's union
     *
     * @param string $type
     * @return boolean
     */
    public static function isUnionType(string $type) : bool
    {
        return \substr($type, 0, \strlen(\PoP\ComponentModel\TypeResolvers\UnionTypeSymbols::UNION_TYPE_NAME_PREFIX)) == \PoP\ComponentModel\TypeResolvers\UnionTypeSymbols::UNION_TYPE_NAME_PREFIX;
    }
    public static function getUnionTypeCollectionName(string $type) : string
    {
        return \PoP\ComponentModel\TypeResolvers\UnionTypeSymbols::UNION_TYPE_NAME_PREFIX . $type;
    }
    /**
     * Extract the original Union type name (i.e. without "*")
     *
     * @param string $unionTypeCollectionName
     * @return string
     */
    public static function removePrefixFromUnionTypeName(string $unionTypeCollectionName) : string
    {
        return \substr($unionTypeCollectionName, \strlen(\PoP\ComponentModel\TypeResolvers\UnionTypeSymbols::UNION_TYPE_NAME_PREFIX));
    }
    /**
     * Extracts the DB key and ID from the resultItem ID
     *
     * @param array $composedDBKeyResultItemID
     * @return void
     */
    public static function extractDBObjectTypeAndID(string $composedDBKeyResultItemID)
    {
        return \explode(\PoP\ComponentModel\TypeResolvers\UnionTypeSymbols::DBOBJECT_COMPOSED_TYPE_ID_SEPARATOR, $composedDBKeyResultItemID);
    }
    /**
     * Extracts the ID from the resultItem ID
     *
     * @param array $composedDBKeyResultItemID
     * @return void
     */
    public static function extractDBObjectID(string $composedDBObjectTypeAndID)
    {
        $elements = \explode(\PoP\ComponentModel\TypeResolvers\UnionTypeSymbols::DBOBJECT_COMPOSED_TYPE_ID_SEPARATOR, $composedDBObjectTypeAndID);
        // If the UnionTypeResolver didn't have a TypeResolver to process the passed object, the Type will not be added
        // In that case, the ID will be on the first position
        return \count($elements) == 1 ? $elements[0] : $elements[1];
    }
    /**
     * Creates a composed string containing the type and ID of the dbObject
     *
     * @param array $composedDBKeyResultItemID
     * @return void
     */
    public static function getDBObjectComposedTypeAndID(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $id) : string
    {
        return $typeResolver->getTypeOutputName() . \PoP\ComponentModel\TypeResolvers\UnionTypeSymbols::DBOBJECT_COMPOSED_TYPE_ID_SEPARATOR . $id;
    }
    /**
     * Return a class or another depending on these possibilities:
     *
     * - If there is more than 1 target type resolver for the Union, return the Union
     * - (By configuration) If there is only one target, return that one directly
     *   and not the Union (since it's more efficient)
     * - If there are none types, return `null`. As a consequence,
     *   the ID is returned as a field, not as a connection
     *
     * @param string $unionTypeResolverClass
     * @return string|null
     */
    public static function getUnionOrTargetTypeResolverClass(string $unionTypeResolverClass) : ?string
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        $unionTypeResolver = $instanceManager->getInstance($unionTypeResolverClass);
        $targetTypeResolverClasses = $unionTypeResolver->getTargetTypeResolverClasses();
        if ($targetTypeResolverClasses) {
            // By configuration: If there is only 1 item, return only that one
            if (\PoP\ComponentModel\ComponentConfiguration::useSingleTypeInsteadOfUnionType()) {
                return \count($targetTypeResolverClasses) == 1 ? $targetTypeResolverClasses[0] : $unionTypeResolverClass;
            }
            return $unionTypeResolverClass;
        }
        return null;
    }
}
