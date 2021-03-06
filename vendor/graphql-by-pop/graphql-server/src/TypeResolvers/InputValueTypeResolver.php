<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use GraphQLByPoP\GraphQLServer\TypeDataLoaders\SchemaDefinitionReferenceTypeDataLoader;
use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\TypeResolvers\AbstractIntrospectionTypeResolver;
class InputValueTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\AbstractIntrospectionTypeResolver
{
    public const NAME = '__InputValue';
    public function getTypeName() : string
    {
        return self::NAME;
    }
    public function getSchemaTypeDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of an input object in GraphQL', 'graphql-server');
    }
    /**
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $inputValue = $resultItem;
        return $inputValue->getID();
    }
    public function getTypeDataLoaderClass() : string
    {
        return \GraphQLByPoP\GraphQLServer\TypeDataLoaders\SchemaDefinitionReferenceTypeDataLoader::class;
    }
}
