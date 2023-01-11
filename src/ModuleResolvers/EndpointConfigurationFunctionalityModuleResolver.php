<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\Plugin;

class EndpointConfigurationFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use EndpointConfigurationFunctionalityModuleResolverTrait;

    public const API_HIERARCHY = Plugin::NAMESPACE . '\api-hierarchy';

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
            self::API_HIERARCHY,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     * @param string $module
     */
    public function getDependedModuleLists($module): array
    {
        switch ($module) {
            case self::API_HIERARCHY:
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
    public function getName($module): string
    {
        switch ($module) {
            case self::API_HIERARCHY:
                return \__('API Hierarchy', 'graphql-api');
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
            case self::API_HIERARCHY:
                return \__('Create a hierarchy of API endpoints extending from other endpoints, and inheriting their properties', 'graphql-api');
        }
        return parent::getDescription($module);
    }
}
