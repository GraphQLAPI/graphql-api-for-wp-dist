<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\QueryRootTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\MutationRootTypeResolver;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
/**
 * Add connections to the QueryRoot and MutationRoot types,
 * so they can be accessed to generate the schema
 */
class RegisterQueryAndMutationRootsRootFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoP\Engine\TypeResolvers\RootTypeResolver::class);
    }
    /**
     * Register the fields for the Standard GraphQL server only,
     * and when nested mutations are disabled
     */
    public static function getFieldNamesToResolve() : array
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        if ($vars['nested-mutations-enabled']) {
            return [];
        }
        return \array_merge(['queryRoot'], \PoP\API\ComponentConfiguration::enableMutations() ? ['mutationRoot'] : []);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['queryRoot' => $translationAPI->__('Get the Query Root type', 'graphql-server'), 'mutationRoot' => $translationAPI->__('Get the Mutation Root type', 'graphql-server')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['queryRoot' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, 'mutationRoot' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'queryRoot':
                return \GraphQLByPoP\GraphQLServer\TypeResolvers\QueryRootTypeResolver::class;
            case 'mutationRoot':
                return \GraphQLByPoP\GraphQLServer\TypeResolvers\MutationRootTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
