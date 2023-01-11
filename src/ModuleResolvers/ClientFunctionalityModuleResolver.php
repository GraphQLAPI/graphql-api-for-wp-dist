<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLByPoP\GraphQLClientsForWP\Module as GraphQLClientsForWPModule;
use GraphQLByPoP\GraphQLClientsForWP\ModuleConfiguration as GraphQLClientsForWPModuleConfiguration;

/**
 * Modules exposing clients to interact with the API
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class ClientFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use ClientFunctionalityModuleResolverTrait;

    public const GRAPHIQL_FOR_SINGLE_ENDPOINT = Plugin::NAMESPACE . '\graphiql-for-single-endpoint';
    public const GRAPHIQL_FOR_CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\graphiql-for-custom-endpoints';
    public const INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT = Plugin::NAMESPACE . '\interactive-schema-for-single-endpoint';
    public const INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\interactive-schema-for-custom-endpoints';
    public const GRAPHIQL_EXPLORER = Plugin::NAMESPACE . '\graphiql-explorer';

    /**
     * Setting options
     */
    public const OPTION_USE_IN_ADMIN_CLIENT = 'use-in-admin-client';
    public const OPTION_USE_IN_ADMIN_PERSISTED_QUERIES = 'use-in-admin-persisted-queries';
    public const OPTION_USE_IN_PUBLIC_CLIENT_FOR_SINGLE_ENDPOINT = 'use-in-public-client-for-single-endpoint';
    public const OPTION_USE_IN_PUBLIC_CLIENT_FOR_CUSTOM_ENDPOINTS = 'use-in-public-client-for-custom-endpoints';

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
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT,
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
            self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS,
            self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS,
            self::GRAPHIQL_EXPLORER,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     * @param string $module
     */
    public function getDependedModuleLists($module): array
    {
        switch ($module) {
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                return [
                    [
                        EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                    ],
                ];
            case self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS:
            case self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS:
                return [
                    [
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::GRAPHIQL_EXPLORER:
                return [];
        }
        return parent::getDependedModuleLists($module);
    }

    /**
     * @param string $module
     */
    public function areRequirementsSatisfied($module): bool
    {
        switch ($module) {
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                /**
                 * Permalink structure must be enabled
                 */
                return !empty(\get_option('permalink_structure'));
        }
        return parent::areRequirementsSatisfied($module);
    }

    /**
     * @param string $module
     */
    public function getName($module): string
    {
        switch ($module) {
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
                return \__('GraphiQL for Single Endpoint', 'graphql-api');
            case self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS:
                return \__('GraphiQL for Custom Endpoints', 'graphql-api');
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                return \__('Interactive Schema for Single Endpoint', 'graphql-api');
            case self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS:
                return \__('Interactive Schema for Custom Endpoints', 'graphql-api');
            case self::GRAPHIQL_EXPLORER:
                return \__('GraphiQL Explorer', 'graphql-api');
            default:
                return $module;
        }
    }

    /**
     * @param string $module
     */
    public function getDescription($module): string
    {
        /** @var GraphQLClientsForWPModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLClientsForWPModule::class)->getConfiguration();
        switch ($module) {
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public GraphiQL client available under <code>%s</code>, to execute queries against the single endpoint. It requires pretty permalinks enabled', 'graphql-api'),
                    $moduleConfiguration->getGraphiQLClientEndpoint()
                );
            case self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS:
                return \__('Enable custom endpoints to be attached their own GraphiQL client, to execute queries against them', 'graphql-api');
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public Interactive Schema client available under <code>%s</code>, to visualize the schema accessible through the single endpoint. It requires pretty permalinks enabled', 'graphql-api'),
                    $moduleConfiguration->getVoyagerClientEndpoint()
                );
            case self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS:
                return \__('Enable custom endpoints to be attached their own Interactive schema client, to visualize the custom schema subset', 'graphql-api');
            case self::GRAPHIQL_EXPLORER:
                return \__('Add the Explorer widget to the GraphiQL client, to simplify coding the query (by point-and-clicking on the fields)', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    /**
     * Default value for an option set by the module
     * @return mixed
     * @param string $module
     * @param string $option
     */
    public function getSettingsDefaultValue($module, $option)
    {
        $defaultValues = [
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT => [
                ModuleSettingOptions::PATH => '/graphiql/',
            ],
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT => [
                ModuleSettingOptions::PATH => '/schema/',
            ],
            self::GRAPHIQL_EXPLORER => [
                self::OPTION_USE_IN_ADMIN_CLIENT => true,
                self::OPTION_USE_IN_ADMIN_PERSISTED_QUERIES => true,
                self::OPTION_USE_IN_PUBLIC_CLIENT_FOR_SINGLE_ENDPOINT => true,
                self::OPTION_USE_IN_PUBLIC_CLIENT_FOR_CUSTOM_ENDPOINTS => true,
            ],
        ];
        return $defaultValues[$module][$option] ?? null;
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     * @param string $module
     */
    public function getSettings($module): array
    {
        $moduleSettings = parent::getSettings($module);
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module === self::GRAPHIQL_FOR_SINGLE_ENDPOINT) {
            $option = ModuleSettingOptions::PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Client path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL path to access the public GraphiQL client', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module === self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT) {
            $option = ModuleSettingOptions::PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Client path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL path to access the public Interactive Schema client', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module === self::GRAPHIQL_EXPLORER) {
            $option = self::OPTION_USE_IN_ADMIN_CLIENT;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Admin client', 'graphql-api'),
                Properties::DESCRIPTION => \__('Use the Explorer in the GraphiQL client in the admin area?', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
            if ($this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::PERSISTED_QUERIES)) {
                $option = self::OPTION_USE_IN_ADMIN_PERSISTED_QUERIES;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Persisted queries', 'graphql-api'),
                    Properties::DESCRIPTION => \__('Use the Explorer when creating persisted queries in the admin area?', 'graphql-api'),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }
            if ($this->getModuleRegistry()->isModuleEnabled(self::GRAPHIQL_FOR_SINGLE_ENDPOINT)) {
                $option = self::OPTION_USE_IN_PUBLIC_CLIENT_FOR_SINGLE_ENDPOINT;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Single endpoint public client', 'graphql-api'),
                    Properties::DESCRIPTION => \__('Use the Explorer in the single endpoint\'s public GraphiQL client?', 'graphql-api'),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }
            if ($this->getModuleRegistry()->isModuleEnabled(self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS)) {
                $option = self::OPTION_USE_IN_PUBLIC_CLIENT_FOR_CUSTOM_ENDPOINTS;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Custom endpoint public clients', 'graphql-api'),
                    Properties::DESCRIPTION => \__('Use the Explorer in the custom endpoints\' public GraphiQL client?', 'graphql-api'),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }
        }
        return $moduleSettings;
    }

    /**
     * Because GraphiQL v2.0 (yet to be integrated) has the
     * Explorer already built-in, there's no need to have a
     * separate module for it.
     *
     * Since this functionality is already built and working,
     * simply hide it.
     *
     * @see https://github.com/leoloso/PoP/issues/1902
     * @param string $module
     */
    public function isHidden($module): bool
    {
        switch ($module) {
            case self::GRAPHIQL_EXPLORER:
                return true;
            default:
                return parent::isHidden($module);
        }
    }

    /**
     * Because GraphiQL v2.0 (yet to be integrated) has the
     * Explorer already built-in, there's no need to have a
     * separate module for it.
     *
     * Since this functionality is already built and working,
     * simply hide it.
     *
     * @see https://github.com/leoloso/PoP/issues/1902
     * @param string $module
     */
    public function areSettingsHidden($module): bool
    {
        switch ($module) {
            case self::GRAPHIQL_EXPLORER:
                return true;
            default:
                return parent::areSettingsHidden($module);
        }
    }
}
