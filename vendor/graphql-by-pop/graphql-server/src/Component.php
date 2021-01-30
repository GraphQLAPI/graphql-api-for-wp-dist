<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer;

use GraphQLByPoP\GraphQLServer\Conditional\AccessControl\ComponentBoot;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use GraphQLByPoP\GraphQLServer\Environment;
use PoP\Engine\Component as EngineComponent;
use PoP\Engine\Environment as EngineEnvironment;
use PoP\Root\Component\CanDisableComponentTrait;
use GraphQLByPoP\GraphQLServer\Configuration\Request;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use GraphQLByPoP\GraphQLServer\Config\ServiceConfiguration;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use GraphQLByPoP\GraphQLRequest\Component as GraphQLRequestComponent;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use GraphQLByPoP\GraphQLQuery\ComponentConfiguration as GraphQLQueryComponentConfiguration;
use GraphQLByPoP\GraphQLRequest\ComponentConfiguration as GraphQLRequestComponentConfiguration;
use GraphQLByPoP\GraphQLServer\DirectiveResolvers\ConditionalOnEnvironment\ExportDirectiveResolver;
use GraphQLByPoP\GraphQLServer\DirectiveResolvers\ConditionalOnEnvironment\RemoveIfNullDirectiveResolver;
/**
 * Initialize component
 */
class Component extends \PoP\Root\Component\AbstractComponent
{
    use YAMLServicesTrait;
    use CanDisableComponentTrait;
    // const VERSION = '0.1.0';
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
        return [\PoP\AccessControl\Component::class];
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
    protected static function doInitialize(array $configuration = [], bool $skipSchema = \false, array $skipSchemaComponentClasses = []) : void
    {
        if (self::isEnabled()) {
            parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
            \GraphQLByPoP\GraphQLServer\ComponentConfiguration::setConfiguration($configuration);
            self::initYAMLServices(\dirname(__DIR__));
            self::maybeInitYAMLSchemaServices(\dirname(__DIR__), $skipSchema);
            \GraphQLByPoP\GraphQLServer\Config\ServiceConfiguration::initialize();
        }
    }
    protected static function resolveEnabled()
    {
        return \GraphQLByPoP\GraphQLRequest\Component::isEnabled();
    }
    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot() : void
    {
        parent::beforeBoot();
        // Initialize classes
        \PoP\ComponentModel\Container\ContainerBuilderUtils::registerTypeResolversFromNamespace(__NAMESPACE__ . '\\TypeResolvers');
        \PoP\ComponentModel\Container\ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Hooks');
        \PoP\ComponentModel\Container\ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers', \false);
        // ContainerBuilderUtils::attachAndRegisterDirectiveResolversFromNamespace(__NAMESPACE__ . '\\DirectiveResolvers', false);
        // Attach the Extensions with a higher priority, so it executes first
        \PoP\ComponentModel\Container\ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers\\Extensions', \false, 100);
        // Conditional on Environment
        // The @export directive depends on the Multiple Query Execution being enabled
        if (\GraphQLByPoP\GraphQLRequest\ComponentConfiguration::enableMultipleQueryExecution()) {
            \GraphQLByPoP\GraphQLServer\DirectiveResolvers\ConditionalOnEnvironment\ExportDirectiveResolver::attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::DIRECTIVERESOLVERS);
        }
        // Attach @removeIfNull?
        if (\GraphQLByPoP\GraphQLServer\ComponentConfiguration::enableRemoveIfNullDirective()) {
            \GraphQLByPoP\GraphQLServer\DirectiveResolvers\ConditionalOnEnvironment\RemoveIfNullDirectiveResolver::attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::DIRECTIVERESOLVERS);
        }
        // Boot conditional on API package being installed
        if (\class_exists('\\PoP\\AccessControl\\Component')) {
            \GraphQLByPoP\GraphQLServer\Conditional\AccessControl\ComponentBoot::beforeBoot();
        }
        // Boot conditional on having variables treated as expressions for @export directive
        if (\GraphQLByPoP\GraphQLQuery\ComponentConfiguration::enableVariablesAsExpressions()) {
            \PoP\ComponentModel\Container\ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers\\ConditionalOnEnvironment\\VariablesAsExpressions');
        }
        // Boot conditional on having embeddable fields
        if (\PoP\API\ComponentConfiguration::enableEmbeddableFields()) {
            \PoP\ComponentModel\Container\ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\FieldResolvers\\ConditionalOnEnvironment\\EmbeddableFields');
        }
    }
}
