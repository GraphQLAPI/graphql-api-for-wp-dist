<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use GraphQLByPoP\GraphQLServer\TypeResolvers\AbstractIntrospectionTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeDataLoaders\SchemaDefinitionReferenceTypeDataLoader;
class EnumValueTypeResolver extends AbstractIntrospectionTypeResolver
{
    public function getTypeName() : string
    {
        return '__EnumValue';
    }
    public function getSchemaTypeDescription() : ?string
    {
        return $this->translationAPI->__('Representation of an Enum value in GraphQL', 'graphql-server');
    }
    /**
     * @return string|int|null
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $enumValue = $resultItem;
        return $enumValue->getID();
    }
    public function getTypeDataLoaderClass() : string
    {
        return SchemaDefinitionReferenceTypeDataLoader::class;
    }
}
