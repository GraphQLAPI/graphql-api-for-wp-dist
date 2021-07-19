<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLAccessControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCacheControlListCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

/**
 * FieldResolver for the Custom Post Types from this plugin
 */
class ListOfCPTEntitiesRootFieldResolver extends AbstractListOfCPTEntitiesRootFieldResolver
{
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'accessControlLists',
            'cacheControlLists',
            'schemaConfigurations',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'accessControlLists':
                return $this->translationAPI->__('Access Control Lists', 'graphql-api');
            case 'cacheControlLists':
                return $this->translationAPI->__('Cache Control Lists', 'graphql-api');
            case 'schemaConfigurations':
                return $this->translationAPI->__('Schema Configurations', 'graphql-api');
            default:
                return parent::getSchemaFieldDescription($typeResolver, $fieldName);
        }
    }

    protected function getFieldCustomPostType(string $fieldName): string
    {
        /** @var GraphQLAccessControlListCustomPostType */
        $accessControlListCustomPostTypeService = $this->instanceManager->getInstance(GraphQLAccessControlListCustomPostType::class);
        /** @var GraphQLCacheControlListCustomPostType */
        $cacheControlListCustomPostTypeService = $this->instanceManager->getInstance(GraphQLCacheControlListCustomPostType::class);
        /** @var GraphQLSchemaConfigurationCustomPostType */
        $schemaConfigurationCustomPostTypeService = $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
        switch ($fieldName) {
            case 'accessControlLists':
                return $accessControlListCustomPostTypeService->getCustomPostType();
            case 'cacheControlLists':
                return $cacheControlListCustomPostTypeService->getCustomPostType();
            case 'schemaConfigurations':
                return $schemaConfigurationCustomPostTypeService->getCustomPostType();
            default:
                return '';
        }
    }
}
