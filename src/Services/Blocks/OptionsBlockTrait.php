<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeValues;

/**
 * Query Execution (endpoint and persisted query) Options block
 */
trait OptionsBlockTrait
{
    /**
     * Given a bool, return its label for rendering
     * @param bool $value
     */
    protected function getBooleanLabel($value): string
    {
        if ($value) {
            return \__('✅ Yes', 'graphql-api');
        }
        return \__('❌ No', 'graphql-api');
    }

    /**
     * @return array<string,string>
     */
    protected function getEnabledDisabledLabels(): array
    {
        return [
            BlockAttributeValues::ENABLED => \__('✅ Yes', 'graphql-api'),
            BlockAttributeValues::DISABLED => \__('❌ No', 'graphql-api'),
        ];
    }
}
