<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer;

use GraphQLByPoP\GraphQLQuery\ComponentConfiguration as GraphQLQueryComponentConfiguration;
use GraphQLByPoP\GraphQLRequest\Component as GraphQLRequestComponent;
use GraphQLByPoP\GraphQLRequest\ComponentConfiguration as GraphQLRequestComponentConfiguration;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLByPoP\GraphQLServer\Configuration\Request;
use GraphQLByPoP\GraphQLServer\Environment;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use PoP\Engine\Component as EngineComponent;
use PoP\Engine\Environment as EngineEnvironment;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;
/**
 * Initialize component
 */
class Component extends \PoP\Root\Component\AbstractComponent
{
    use CanDisableComponentTrait;
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses() : array
    {
        return [\GraphQLByPoP\GraphQLRequest\Component::class];
    }
    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return array
     */
    public static function getDependedConditionalComponentClasses() : array
    {
        return [\PoP\AccessControl\Component::class, \PoP\CacheControl\Component::class];
    }
    /**
     * Set the default component configuration
     *
     * @param array<string, mixed> $componentClassConfiguration
     */
    public static function customizeComponentClassConfiguration(array &$componentClassConfiguration) : void
    {
        // The mutation scheme can be set by param ?mutation_scheme=..., with values:
        // - "standard" => Use QueryRoot and MutationRoot
        // - "nested" => Use Root, and nested mutations with redundant root fields
        // - "lean_nested" => Use Root, and nested mutations without redundant root fields
        if (\GraphQLByPoP\GraphQLServer\Environment::enableSettingMutationSchemeByURLParam()) {
            if ($mutationScheme = \GraphQLByPoP\GraphQLServer\Configuration\Request::getMutationScheme()) {
                $componentClassConfiguration[self::class][\GraphQLByPoP\GraphQLServer\Environment::ENABLE_NESTED_MUTATIONS] = $mutationScheme != \GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes::STANDARD;
                $componentClassConfiguration[\PoP\Engine\Component::class][\PoP\Engine\Environment::DISABLE_REDUNDANT_ROOT_TYPE_MUTATION_FIELDS] = $mutationScheme == \GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS;
            }
        }
        // Enable GraphQL Introspection for PQL by doing ?enable_graphql_introspection=1
        if (\GraphQLByPoP\GraphQLServer\Environment::enableEnablingGraphQLIntrospectionByURLParam()) {
            $enableGraphQLIntrospection = \GraphQLByPoP\GraphQLServer\Configuration\Request::enableGraphQLIntrospection();
            if ($enableGraphQLIntrospection !== null) {
                $componentClassConfiguration[self::class][\GraphQLByPoP\GraphQLServer\Environment::ENABLE_GRAPHQL_INTROSPECTION] = $enableGraphQLIntrospection;
            }
        }
    }
    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function initializeContainerServices(array $configuration = [], bool $skipSchema = \false, array $skipSchemaComponentClasses = []) : void
    {
        if (self::isEnabled()) {
            parent::initializeContainerServices($configuration, $skipSchema, $skipSchemaComponentClasses);
            \GraphQLByPoP\GraphQLServer\ComponentConfiguration::setConfiguration($configuration);
            self::initYAMLServices(\dirname(__DIR__));
            self::initYAMLServices(\dirname(__DIR__), '/Overrides');
            self::maybeInitYAMLSchemaServices(\dirname(__DIR__), $skipSchema);
            // Boot conditional on having variables treated as expressions for @export directive
            if (\GraphQLByPoP\GraphQLQuery\ComponentConfiguration::enableVariablesAsExpressions()) {
                self::maybeInitPHPSchemaServices(\dirname(__DIR__), $skipSchema, '/ConditionalOnEnvironment/VariablesAsExpressions');
            }
            // Boot conditional on having embeddable fields
            if (\PoP\API\ComponentConfiguration::enableEmbeddableFields()) {
                self::maybeInitPHPSchemaServices(\dirname(__DIR__), $skipSchema, '/ConditionalOnEnvironment/EmbeddableFields');
            }
            // The @export directive depends on the Multiple Query Execution being enabled
            if (\GraphQLByPoP\GraphQLRequest\ComponentConfiguration::enableMultipleQueryExecution()) {
                self::maybeInitPHPSchemaServices(\dirname(__DIR__), $skipSchema, '/ConditionalOnEnvironment/MultipleQueryExecution');
            }
            // Attach @removeIfNull?
            if (\GraphQLByPoP\GraphQLServer\ComponentConfiguration::enableRemoveIfNullDirective()) {
                self::maybeInitPHPSchemaServices(\dirname(__DIR__), $skipSchema, '/ConditionalOnEnvironment/RemoveIfNull');
            }
            if (\class_exists('\\PoP\\CacheControl\\Component') && !\in_array(\PoP\CacheControl\Component::class, $skipSchemaComponentClasses) && \class_exists('\\PoP\\AccessControl\\Component') && !\in_array(\PoP\AccessControl\Component::class, $skipSchemaComponentClasses) && \PoP\AccessControl\ComponentConfiguration::canSchemaBePrivate()) {
                self::maybeInitPHPSchemaServices(\dirname(__DIR__), $skipSchema, '/Conditional/CacheControl/Conditional/AccessControl/ConditionalOnEnvironment/PrivateSchema');
            }
        }
    }
    /**
     * Initialize services for the system container
     *
     * @param array<string, mixed> $configuration
     */
    protected static function initializeSystemContainerServices(array $configuration = []) : void
    {
        if (self::isEnabled()) {
            parent::initializeSystemContainerServices($configuration);
            self::initYAMLSystemContainerServices(\dirname(__DIR__));
        }
    }
    protected static function resolveEnabled()
    {
        return \GraphQLByPoP\GraphQLRequest\Component::isEnabled();
    }
}
