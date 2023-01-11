<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\Plugin;

class UserInterfaceFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use UserInterfaceFunctionalityModuleResolverTrait;

    public const EXCERPT_AS_DESCRIPTION = Plugin::NAMESPACE . '\excerpt-as-description';
    public const WELCOME_GUIDES = Plugin::NAMESPACE . '\welcome-guides';

    /**
     * @var \GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface|null
     */
    private $markdownContentParser;

    /**
     * @param \GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface $markdownContentParser
     */
    final public function setMarkdownContentParser($markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser = $this->markdownContentParser ?? $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::EXCERPT_AS_DESCRIPTION,
            self::WELCOME_GUIDES,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     * @param string $module
     */
    public function getDependedModuleLists($module): array
    {
        switch ($module) {
            case self::EXCERPT_AS_DESCRIPTION:
                return [];
            case self::WELCOME_GUIDES:
                return [
                    [
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    /**
     * @param string $module
     */
    public function areRequirementsSatisfied($module): bool
    {
        switch ($module) {
            case self::WELCOME_GUIDES:
                /**
                 * WordPress 5.5 or above, or Gutenberg 8.2 or above
                 */
                return
                    \is_wp_version_compatible('5.5') ||
                    (
                        defined('GUTENBERG_VERSION') &&
                        \version_compare(constant('GUTENBERG_VERSION'), '8.2', '>=')
                    );
        }
        return parent::areRequirementsSatisfied($module);
    }

    /**
     * @param string $module
     */
    public function isHidden($module): bool
    {
        switch ($module) {
            case self::WELCOME_GUIDES:
                return true;
        }
        return parent::isHidden($module);
    }

    /**
     * @param string $module
     */
    public function getName($module): string
    {
        switch ($module) {
            case self::EXCERPT_AS_DESCRIPTION:
                return \__('Excerpt as Description', 'graphql-api');
            case self::WELCOME_GUIDES:
                return \__('Welcome Guides', 'graphql-api');
            default:
                return $module;
        }
    }

    /**
     * @param string $module
     */
    public function getDescription($module): string
    {
        switch ($module) {
            case self::EXCERPT_AS_DESCRIPTION:
                return \__('Provide a description of the different entities (Custom Endpoints, Persisted Queries, and others) through their excerpt', 'graphql-api');
            case self::WELCOME_GUIDES:
                return sprintf(
                    \__('Display welcome guides which demonstrate how to use the plugin\'s different functionalities. <em>It requires WordPress version \'%s\' or above, or Gutenberg version \'%s\' or above</em>', 'graphql-api'),
                    '5.5',
                    '8.2'
                );
        }
        return parent::getDescription($module);
    }

    /**
     * @param string $module
     */
    public function isEnabledByDefault($module): bool
    {
        switch ($module) {
            case self::WELCOME_GUIDES:
                return false;
        }
        return parent::isEnabledByDefault($module);
    }
}
