<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks;

use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Blocks\GraphQLByPoPBlockTrait;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\BlockCategories\EndpointBlockCategory;
use GraphQLAPI\GraphQLAPI\Blocks\AbstractQueryExecutionOptionsBlock;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;

/**
 * Endpoint Options block
 */
class EndpointOptionsBlock extends AbstractQueryExecutionOptionsBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_IS_GRAPHIQL_ENABLED = 'isGraphiQLEnabled';
    public const ATTRIBUTE_NAME_IS_VOYAGER_ENABLED = 'isVoyagerEnabled';

    protected function getBlockName(): string
    {
        return 'endpoint-options';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var EndpointBlockCategory
         */
        $blockCategory = $instanceManager->getInstance(EndpointBlockCategory::class);
        return $blockCategory;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    protected function getBlockContent(array $attributes, string $content): string
    {
        $blockContent = parent::getBlockContent($attributes, $content);
        $moduleRegistry = ModuleRegistryFacade::getInstance();

        $blockContentPlaceholder = '<p><strong>%s</strong> %s</p>';
        if ($moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_CUSTOM_ENDPOINTS)) {
            $blockContent .= sprintf($blockContentPlaceholder, \__('Expose GraphiQL client?', 'graphql-api'), $this->getBooleanLabel($attributes[self::ATTRIBUTE_NAME_IS_GRAPHIQL_ENABLED] ?? true));
        }
        if ($moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS)) {
            $blockContent .= sprintf($blockContentPlaceholder, \__('Expose the Interactive Schema client?', 'graphql-api'), $this->getBooleanLabel($attributes[self::ATTRIBUTE_NAME_IS_VOYAGER_ENABLED] ?? true));
        }

        return $blockContent;
    }

    /**
     * Pass localized data to the block
     *
     * @return array<string, mixed>
     */
    protected function getLocalizedData(): array
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        return array_merge(parent::getLocalizedData(), [
            'isGraphiQLEnabled' => $moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_CUSTOM_ENDPOINTS),
            'isVoyagerEnabled' => $moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS),
        ]);
    }
}
