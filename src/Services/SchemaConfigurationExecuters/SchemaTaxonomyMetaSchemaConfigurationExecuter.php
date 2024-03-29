<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\MetaSchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaTaxonomyMetaBlock;
use PoPCMSSchema\TaxonomyMeta\Environment as TaxonomyMetaEnvironment;
use PoPCMSSchema\TaxonomyMeta\Module as TaxonomyMetaModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaTaxonomyMetaSchemaConfigurationExecuter extends AbstractSchemaMetaSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaTaxonomyMetaBlock|null
     */
    private $schemaConfigSchemaTaxonomyMetaBlock;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaTaxonomyMetaBlock $schemaConfigSchemaTaxonomyMetaBlock
     */
    final public function setSchemaConfigSchemaTaxonomyMetaBlock($schemaConfigSchemaTaxonomyMetaBlock): void
    {
        $this->schemaConfigSchemaTaxonomyMetaBlock = $schemaConfigSchemaTaxonomyMetaBlock;
    }
    final protected function getSchemaConfigSchemaTaxonomyMetaBlock(): SchemaConfigSchemaTaxonomyMetaBlock
    {
        /** @var SchemaConfigSchemaTaxonomyMetaBlock */
        return $this->schemaConfigSchemaTaxonomyMetaBlock = $this->schemaConfigSchemaTaxonomyMetaBlock ?? $this->instanceManager->getInstance(SchemaConfigSchemaTaxonomyMetaBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return MetaSchemaTypeModuleResolver::SCHEMA_TAXONOMY_META;
    }

    protected function getEntriesHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            TaxonomyMetaModule::class,
            TaxonomyMetaEnvironment::TAXONOMY_META_ENTRIES
        );
    }

    protected function getBehaviorHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            TaxonomyMetaModule::class,
            TaxonomyMetaEnvironment::TAXONOMY_META_BEHAVIOR
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaTaxonomyMetaBlock();
    }
}
