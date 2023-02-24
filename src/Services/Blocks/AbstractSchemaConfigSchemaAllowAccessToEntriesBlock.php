<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

abstract class AbstractSchemaConfigSchemaAllowAccessToEntriesBlock extends AbstractSchemaConfigCustomizableConfigurationBlock
{
    use AllowAccessToEntriesBlockTrait;

    /**
     * Pass localized data to the block
     *
     * @return array<string,mixed>
     */
    protected function getLocalizedData(): array
    {
        return array_merge(
            parent::getLocalizedData(),
            $this->getDefaultBehaviorLocalizedData()
        );
    }

    /**
     * @param array<string,mixed> $attributes
     * @param string $content
     */
    protected function doRenderBlock($attributes, $content): string
    {
        return $this->renderAllowAccessToEntriesBlock($attributes);
    }
}
