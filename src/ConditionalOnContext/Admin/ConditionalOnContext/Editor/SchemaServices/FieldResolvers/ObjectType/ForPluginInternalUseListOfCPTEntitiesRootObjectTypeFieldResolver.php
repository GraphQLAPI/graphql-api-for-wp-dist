<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\ConditionalOnContext\Editor\SchemaServices\FieldResolvers\ObjectType;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * ObjectTypeFieldResolver for the Custom Post Types from this plugin
 */
class ForPluginInternalUseListOfCPTEntitiesRootObjectTypeFieldResolver extends AbstractForPluginInternalUseListOfCPTEntitiesRootObjectTypeFieldResolver
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType|null
     */
    private $graphQLSchemaConfigurationCustomPostType;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType
     */
    final public function setGraphQLSchemaConfigurationCustomPostType($graphQLSchemaConfigurationCustomPostType): void
    {
        $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
    }
    final protected function getGraphQLSchemaConfigurationCustomPostType(): GraphQLSchemaConfigurationCustomPostType
    {
        /** @var GraphQLSchemaConfigurationCustomPostType */
        return $this->graphQLSchemaConfigurationCustomPostType = $this->graphQLSchemaConfigurationCustomPostType ?? $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'schemaConfigurations',
        ];
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName): ?string
    {
        switch ($fieldName) {
            case 'schemaConfigurations':
                return $this->__('Schema Configurations', 'graphql-api');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }

    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function getFieldCustomPostType($fieldDataAccessor): string
    {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'schemaConfigurations':
                return $this->getGraphQLSchemaConfigurationCustomPostType()->getCustomPostType();
            default:
                return '';
        }
    }
}
