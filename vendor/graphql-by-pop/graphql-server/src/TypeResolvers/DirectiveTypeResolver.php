<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use GraphQLByPoP\GraphQLServer\TypeDataLoaders\SchemaDefinitionReferenceTypeDataLoader;
use GraphQLByPoP\GraphQLServer\TypeResolvers\AbstractIntrospectionTypeResolver;
class DirectiveTypeResolver extends AbstractIntrospectionTypeResolver
{
    public function getTypeName() : string
    {
        return '__Directive';
    }
    public function getSchemaTypeDescription() : ?string
    {
        return $this->translationAPI->__('A GraphQL directive in the data graph', 'graphql-server');
    }
    /**
     * @return string|int|null
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $directive = $resultItem;
        return $directive->getID();
    }
    public function getTypeDataLoaderClass() : string
    {
        return SchemaDefinitionReferenceTypeDataLoader::class;
    }
}
