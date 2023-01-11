<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\SchemaConfigurationBlockCategory;

abstract class AbstractSchemaConfigBlock extends AbstractBlock implements SchemaConfigEditorBlockServiceTagInterface
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\BlockCategories\SchemaConfigurationBlockCategory|null
     */
    private $schemaConfigurationBlockCategory;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\BlockCategories\SchemaConfigurationBlockCategory $schemaConfigurationBlockCategory
     */
    final public function setSchemaConfigurationBlockCategory($schemaConfigurationBlockCategory): void
    {
        $this->schemaConfigurationBlockCategory = $schemaConfigurationBlockCategory;
    }
    final protected function getSchemaConfigurationBlockCategory(): SchemaConfigurationBlockCategory
    {
        /** @var SchemaConfigurationBlockCategory */
        return $this->schemaConfigurationBlockCategory = $this->schemaConfigurationBlockCategory ?? $this->instanceManager->getInstance(SchemaConfigurationBlockCategory::class);
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->getSchemaConfigurationBlockCategory();
    }

    public function getBlockPriority(): int
    {
        return 10;
    }
}
