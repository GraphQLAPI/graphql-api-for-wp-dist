<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\TypeResolvers\AbstractIntrospectionTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeDataLoaders\SchemaDefinitionReferenceTypeDataLoader;
class FieldTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\AbstractIntrospectionTypeResolver
{
    public const NAME = '__Field';
    public function getTypeName() : string
    {
        return self::NAME;
    }
    public function getSchemaTypeDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a GraphQL type\'s field', 'graphql-server');
    }
    /**
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $field = $resultItem;
        return $field->getID();
    }
    public function getTypeDataLoaderClass() : string
    {
        return \GraphQLByPoP\GraphQLServer\TypeDataLoaders\SchemaDefinitionReferenceTypeDataLoader::class;
    }
}
