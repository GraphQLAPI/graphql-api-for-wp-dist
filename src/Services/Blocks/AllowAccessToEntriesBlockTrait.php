<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Constants\BlockAttributeNames;
use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use PoPSchema\SchemaCommons\Constants\Behaviors;

trait AllowAccessToEntriesBlockTrait
{
    /**
     * Pass localized data to the block
     *
     * @return array<string,mixed>
     */
    protected function getDefaultBehaviorLocalizedData(): array
    {
        return [
            'defaultBehavior' => $this->getDefaultBehavior(),
        ];
    }

    protected function getDefaultBehavior(): string
    {
        return PluginEnvironment::areUnsafeDefaultsEnabled()
            ? Behaviors::DENY
            : Behaviors::ALLOW;
    }

    /**
     * @param array<string,mixed> $attributes
     */
    protected function renderAllowAccessToEntriesBlock($attributes): string
    {
        $placeholder = '<p><strong>%s</strong></p>%s';
        $entries = $attributes[BlockAttributeNames::ENTRIES] ?? [];
        $behavior = $attributes[BlockAttributeNames::BEHAVIOR] ?? $this->getDefaultBehavior();
        switch ($behavior) {
            case Behaviors::ALLOW:
                return sprintf('✅ %s', $this->__('Allow access', 'graphql-api'));
            case Behaviors::DENY:
                return sprintf('❌ %s', $this->__('Deny access', 'graphql-api'));
            default:
                return $behavior;
        }
    }

    abstract protected function getRenderBlockLabel(): string;
}
