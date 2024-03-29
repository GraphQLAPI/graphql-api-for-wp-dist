<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\MetaSchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaCommentMetaBlock;
use PoPCMSSchema\CommentMeta\Environment as CommentMetaEnvironment;
use PoPCMSSchema\CommentMeta\Module as CommentMetaModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaCommentMetaSchemaConfigurationExecuter extends AbstractSchemaMetaSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaCommentMetaBlock|null
     */
    private $schemaConfigSchemaCommentMetaBlock;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigSchemaCommentMetaBlock $schemaConfigSchemaCommentMetaBlock
     */
    final public function setSchemaConfigSchemaCommentMetaBlock($schemaConfigSchemaCommentMetaBlock): void
    {
        $this->schemaConfigSchemaCommentMetaBlock = $schemaConfigSchemaCommentMetaBlock;
    }
    final protected function getSchemaConfigSchemaCommentMetaBlock(): SchemaConfigSchemaCommentMetaBlock
    {
        /** @var SchemaConfigSchemaCommentMetaBlock */
        return $this->schemaConfigSchemaCommentMetaBlock = $this->schemaConfigSchemaCommentMetaBlock ?? $this->instanceManager->getInstance(SchemaConfigSchemaCommentMetaBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return MetaSchemaTypeModuleResolver::SCHEMA_COMMENT_META;
    }

    protected function getEntriesHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            CommentMetaModule::class,
            CommentMetaEnvironment::COMMENT_META_ENTRIES
        );
    }

    protected function getBehaviorHookName(): string
    {
        return ModuleConfigurationHelpers::getHookName(
            CommentMetaModule::class,
            CommentMetaEnvironment::COMMENT_META_BEHAVIOR
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaCommentMetaBlock();
    }
}
