<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use PoPSchema\SchemaCommons\Constants\Behaviors;

abstract class AbstractSchemaConfigSchemaAllowAccessToEntriesBlock extends AbstractSchemaConfigCustomizableConfigurationBlock
{
    public const ATTRIBUTE_NAME_ENTRIES = 'entries';
    public const ATTRIBUTE_NAME_BEHAVIOR = 'behavior';

    /**
     * Pass localized data to the block
     *
     * @return array<string,mixed>
     */
    protected function getLocalizedData(): array
    {
        return array_merge(
            parent::getLocalizedData(),
            [
                'defaultBehavior' => $this->getDefaultBehavior(),
            ]
        );
    }

    protected function getDefaultBehavior(): string
    {
        return PluginEnvironment::areUnsafeDefaultsEnabled()
            ? Behaviors::DENY
            : Behaviors::ALLOW;
    }

    /**
     * @param array<string,mixed> $attributes
     * @param string $content
     */
    protected function doRenderBlock($attributes, $content): string
    {
        $placeholder = '<p><strong>%s</strong></p>%s';
        $entries = $attributes[self::ATTRIBUTE_NAME_ENTRIES] ?? [];
        $behavior = $attributes[self::ATTRIBUTE_NAME_BEHAVIOR] ?? $this->getDefaultBehavior();
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
