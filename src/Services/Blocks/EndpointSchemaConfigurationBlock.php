<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\EndpointBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockRenderingHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\CPTUtils;

/**
 * SchemaConfiguration block
 */
class EndpointSchemaConfigurationBlock extends AbstractBlock implements PersistedQueryEndpointEditorBlockServiceTagInterface, EndpointEditorBlockServiceTagInterface
{
    use MainPluginBlockTrait;

    public const ATTRIBUTE_NAME_SCHEMA_CONFIGURATION = 'schemaConfiguration';
    /**
     * These consts must be integer!
     */
    public const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT = 0;
    public const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE = -1;
    public const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT = -2;

    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Helpers\BlockRenderingHelpers|null
     */
    private $blockRenderingHelpers;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Helpers\CPTUtils|null
     */
    private $cptUtils;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\BlockCategories\EndpointBlockCategory|null
     */
    private $endpointBlockCategory;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Helpers\BlockRenderingHelpers $blockRenderingHelpers
     */
    final public function setBlockRenderingHelpers($blockRenderingHelpers): void
    {
        $this->blockRenderingHelpers = $blockRenderingHelpers;
    }
    final protected function getBlockRenderingHelpers(): BlockRenderingHelpers
    {
        /** @var BlockRenderingHelpers */
        return $this->blockRenderingHelpers = $this->blockRenderingHelpers ?? $this->instanceManager->getInstance(BlockRenderingHelpers::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Helpers\CPTUtils $cptUtils
     */
    final public function setCPTUtils($cptUtils): void
    {
        $this->cptUtils = $cptUtils;
    }
    final protected function getCPTUtils(): CPTUtils
    {
        /** @var CPTUtils */
        return $this->cptUtils = $this->cptUtils ?? $this->instanceManager->getInstance(CPTUtils::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\BlockCategories\EndpointBlockCategory $endpointBlockCategory
     */
    final public function setEndpointBlockCategory($endpointBlockCategory): void
    {
        $this->endpointBlockCategory = $endpointBlockCategory;
    }
    final protected function getEndpointBlockCategory(): EndpointBlockCategory
    {
        /** @var EndpointBlockCategory */
        return $this->endpointBlockCategory = $this->endpointBlockCategory ?? $this->instanceManager->getInstance(EndpointBlockCategory::class);
    }

    protected function getBlockName(): string
    {
        return 'schema-configuration';
    }

    public function getBlockPriority(): int
    {
        return 180;
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->getEndpointBlockCategory();
    }

    protected function isDynamicBlock(): bool
    {
        return true;
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
                'isAPIHierarchyEnabled' => $this->getModuleRegistry()->isModuleEnabled(EndpointConfigurationFunctionalityModuleResolver::API_HIERARCHY),
            ]
        );
    }

    /**
     * @param array<string,mixed> $attributes
     * @param string $content
     */
    public function renderBlock($attributes, $content): string
    {
        /**
         * Print the list of all the contained Access Control blocks
         */
        $blockContentPlaceholder = <<<EOF
        <div class="%s">
            <h3 class="%s">%s</strong></h3>
            %s
        </div>
EOF;
        $schemaConfigurationContent = '';
        $schemaConfigurationID = $attributes[self::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION] ?? null;
        if ($schemaConfigurationID == self::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT) {
            $schemaConfigurationContent = \__('Default', 'graphql-api');
        } elseif ($schemaConfigurationID == self::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE) {
            $schemaConfigurationContent = \__('None', 'graphql-api');
        } elseif ($schemaConfigurationID == self::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT) {
            $schemaConfigurationContent = \__('Inherit from parent', 'graphql-api');
        } elseif ($schemaConfigurationID > 0) {
            $schemaConfigurationObject = \get_post($schemaConfigurationID);
            if (!is_null($schemaConfigurationObject)) {
                $schemaConfigurationDescription = $this->getCPTUtils()->getCustomPostDescription($schemaConfigurationObject);
                $permalink = \get_permalink($schemaConfigurationObject->ID);
                $schemaConfigurationContent = ($permalink ?
                    \sprintf(
                        '<code><a href="%s">%s</a></code>',
                        $permalink,
                        $this->getBlockRenderingHelpers()->getCustomPostTitle($schemaConfigurationObject)
                    ) :
                    \sprintf(
                        '<code>%s</code>',
                        $this->getBlockRenderingHelpers()->getCustomPostTitle($schemaConfigurationObject)
                    )
                ) . ($schemaConfigurationDescription ?
                    '<br/><small>' . $schemaConfigurationDescription . '</small>'
                    : ''
                );
            }
        }
        $className = $this->getBlockClassName();
        return sprintf(
            $blockContentPlaceholder,
            $className,
            $className . '-front',
            \__('Schema Configuration', 'graphql-api'),
            $schemaConfigurationContent
        );
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

    /**
     * Register style-index.css
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }
}
