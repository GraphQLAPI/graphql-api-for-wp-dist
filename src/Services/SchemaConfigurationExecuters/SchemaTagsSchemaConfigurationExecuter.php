<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaTagsBlock;
use PoPCMSSchema\Tags\Module as TagsModule;
use PoPCMSSchema\Tags\Environment as TagsEnvironment;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaTagsSchemaConfigurationExecuter extends AbstractCustomizableConfigurationSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaTagsBlock|null
     */
    private $schemaConfigTagsBlock;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaTagsBlock $schemaConfigTagsBlock
     */
    final public function setSchemaConfigSchemaTagsBlock($schemaConfigTagsBlock): void
    {
        $this->schemaConfigTagsBlock = $schemaConfigTagsBlock;
    }
    final protected function getSchemaConfigSchemaTagsBlock(): SchemaConfigSchemaTagsBlock
    {
        /** @var SchemaConfigSchemaTagsBlock */
        return $this->schemaConfigTagsBlock = $this->schemaConfigTagsBlock ?? $this->instanceManager->getInstance(SchemaConfigSchemaTagsBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_CATEGORIES;
    }

    /**
     * @param int $schemaConfigurationID
     */
    protected function doExecuteSchemaConfiguration($schemaConfigurationID): void
    {
        $schemaConfigBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigBlockDataItem === null) {
            return;
        }
        $includedTagTaxonomies = $schemaConfigBlockDataItem['attrs'][SchemaConfigSchemaTagsBlock::ATTRIBUTE_NAME_INCLUDED_TAG_TAXONOMIES] ?? [];
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = ModuleConfigurationHelpers::getHookName(
            TagsModule::class,
            TagsEnvironment::QUERYABLE_TAG_TAXONOMIES
        );
        \add_filter(
            $hookName,
            function () use ($includedTagTaxonomies) {
                return $includedTagTaxonomies;
            },
            PHP_INT_MAX
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaTagsBlock();
    }
}
