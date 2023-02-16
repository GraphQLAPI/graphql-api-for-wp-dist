<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Constants\ConfigurationDefaultValues;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\WPDataModel\WPDataModelProviderInterface;

class SchemaConfigSchemaTagsBlock extends AbstractSchemaConfigCustomizableConfigurationBlock
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    public const ATTRIBUTE_NAME_INCLUDED_TAG_TAXONOMIES = 'includedTagTaxonomies';

    /**
     * @var \GraphQLAPI\GraphQLAPI\WPDataModel\WPDataModelProviderInterface|null
     */
    private $wpDataModelProvider;

    /**
     * @param \GraphQLAPI\GraphQLAPI\WPDataModel\WPDataModelProviderInterface $wpDataModelProvider
     */
    final public function setWPDataModelProvider($wpDataModelProvider): void
    {
        $this->wpDataModelProvider = $wpDataModelProvider;
    }
    final protected function getWPDataModelProvider(): WPDataModelProviderInterface
    {
        /** @var WPDataModelProviderInterface */
        return $this->wpDataModelProvider = $this->wpDataModelProvider ?? $this->instanceManager->getInstance(WPDataModelProviderInterface::class);
    }

    protected function getBlockName(): string
    {
        return 'schema-config-schema-tags';
    }

    public function getBlockPriority(): int
    {
        return 10080;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_TAGS;
    }

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
                'possibleTagTaxonomies' => $this->getWPDataModelProvider()->getFilteredNonGraphQLAPIPluginTagTaxonomies(),
                'defaultTagTaxonomies' => ConfigurationDefaultValues::DEFAULT_TAG_TAXONOMIES,
            ]
        );
    }

    /**
     * @param array<string,mixed> $attributes
     * @param string $content
     */
    protected function doRenderBlock($attributes, $content): string
    {
        $values = $attributes[self::ATTRIBUTE_NAME_INCLUDED_TAG_TAXONOMIES] ?? [];
        return sprintf(
            '<p><strong>%s</strong></p>%s',
            $this->__('Included tag taxonomies', 'graphql-api'),
            $values ?
                sprintf(
                    '<ul><li><code>%s</code></li></ul>',
                    implode('</code></li><li><code>', $values)
                ) :
                sprintf(
                    '<p><em>%s</em></p>',
                    \__('(not set)', 'graphql-api')
                )
        );
    }

    protected function getBlockTitle(): string
    {
        return \__('Tags', 'graphql-api');
    }

    /**
     * Register style-index.css
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

    /**
     * Add the locale language to the localized data?
     */
    protected function addLocalLanguage(): bool
    {
        return true;
    }

    /**
     * Default language for the script/component's documentation
     */
    protected function getDefaultLanguage(): ?string
    {
        // English
        return 'en';
    }
}
