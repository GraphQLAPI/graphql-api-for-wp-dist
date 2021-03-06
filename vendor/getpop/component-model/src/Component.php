<?php

declare (strict_types=1);
namespace PoP\ComponentModel;

use PoP\ComponentModel\Component\ApplicationEvents;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Facades\AttachableExtensions\AttachExtensionServiceFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Component\AbstractComponent;
/**
 * Initialize component
 */
class Component extends \PoP\Root\Component\AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses() : array
    {
        return [\PoP\Definitions\Component::class, \PoP\FieldQuery\Component::class];
    }
    public static function getDependedMigrationPlugins() : array
    {
        $packageName = \basename(\dirname(__DIR__));
        $folder = \dirname(__DIR__, 2);
        return [$folder . '/migrate-' . $packageName . '/initialize.php'];
    }
    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function initializeContainerServices(array $configuration = [], bool $skipSchema = \false, array $skipSchemaComponentClasses = []) : void
    {
        parent::initializeContainerServices($configuration, $skipSchema, $skipSchemaComponentClasses);
        \PoP\ComponentModel\ComponentConfiguration::setConfiguration($configuration);
        self::initYAMLServices(\dirname(__DIR__));
        self::maybeInitYAMLSchemaServices(\dirname(__DIR__), $skipSchema);
    }
    /**
     * Initialize services for the system container
     *
     * @param array<string, mixed> $configuration
     */
    protected static function initializeSystemContainerServices(array $configuration = []) : void
    {
        parent::initializeSystemContainerServices($configuration);
        self::initYAMLSystemContainerServices(\dirname(__DIR__));
    }
    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot() : void
    {
        parent::beforeBoot();
        // Initialize the Component Configuration
        \PoP\ComponentModel\ComponentConfiguration::init();
        // Attach class extensions
        \PoP\ComponentModel\Facades\AttachableExtensions\AttachExtensionServiceFacade::getInstance()->attachExtensions(\PoP\ComponentModel\Component\ApplicationEvents::BEFORE_BOOT);
    }
    /**
     * Boot component
     *
     * @return void
     */
    public static function afterBoot() : void
    {
        parent::afterBoot();
        // Attach class extensions
        \PoP\ComponentModel\Facades\AttachableExtensions\AttachExtensionServiceFacade::getInstance()->attachExtensions(\PoP\ComponentModel\Component\ApplicationEvents::AFTER_BOOT);
    }
    /**
     * Define runtime constants
     */
    protected static function defineRuntimeConstants(array $configuration = [], bool $skipSchema = \false, array $skipSchemaComponentClasses = []) : void
    {
        // This Constant is needed to be able to retrieve the timestamp and replace it for nothing when generating the ETag,
        // so that this random value does not modify the hash of the overall html output
        \define('POP_CONSTANT_UNIQUE_ID', \PoP\ComponentModel\Misc\GeneralUtils::generateRandomString());
        \define('POP_CONSTANT_RAND', \rand());
        \define('POP_CONSTANT_TIME', \time());
        // This value will be used in the response. If compact, make sure each JS Key is unique
        \define('POP_RESPONSE_PROP_SUBMODULES', \PoP\ComponentModel\Environment::compactResponseJsonKeys() ? 'ms' : 'submodules');
    }
}
