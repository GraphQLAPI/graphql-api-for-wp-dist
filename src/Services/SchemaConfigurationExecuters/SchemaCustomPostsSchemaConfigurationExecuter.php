<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaCustomPostsBlock;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\CustomPosts\Environment as CustomPostsEnvironment;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaCustomPostsSchemaConfigurationExecuter extends AbstractCustomizableConfigurationSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaCustomPostsBlock|null
     */
    private $schemaConfigSchemaCustomPostsBlock;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaCustomPostsBlock $schemaConfigSchemaCustomPostsBlock
     */
    final public function setSchemaConfigSchemaCustomPostsBlock($schemaConfigSchemaCustomPostsBlock): void
    {
        $this->schemaConfigSchemaCustomPostsBlock = $schemaConfigSchemaCustomPostsBlock;
    }
    final protected function getSchemaConfigSchemaCustomPostsBlock(): SchemaConfigSchemaCustomPostsBlock
    {
        /** @var SchemaConfigSchemaCustomPostsBlock */
        return $this->schemaConfigSchemaCustomPostsBlock = $this->schemaConfigSchemaCustomPostsBlock ?? $this->instanceManager->getInstance(SchemaConfigSchemaCustomPostsBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS;
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
        $includedCustomPostTypes = $schemaConfigBlockDataItem['attrs'][SchemaConfigSchemaCustomPostsBlock::ATTRIBUTE_NAME_INCLUDED_CUSTOM_POST_TYPES] ?? [];
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = ModuleConfigurationHelpers::getHookName(
            CustomPostsModule::class,
            CustomPostsEnvironment::QUERYABLE_CUSTOMPOST_TYPES
        );
        \add_filter(
            $hookName,
            function () use ($includedCustomPostTypes) {
                return $includedCustomPostTypes;
            },
            PHP_INT_MAX
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaCustomPostsBlock();
    }
}
