<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceSchemaDefinitionResolverTrait;
abstract class AbstractFieldInterfaceResolver implements \PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface
{
    use FieldInterfaceSchemaDefinitionResolverTrait;
    public static function getFieldNamesToResolve() : array
    {
        return self::getFieldNamesToImplement();
    }
    public static function getImplementedInterfaceClasses() : array
    {
        return [];
    }
    public function getNamespace() : string
    {
        return \PoP\ComponentModel\Schema\SchemaHelpers::getSchemaNamespace(\get_called_class());
    }
    public final function getNamespacedInterfaceName() : string
    {
        return \PoP\ComponentModel\Schema\SchemaHelpers::getSchemaNamespacedName($this->getNamespace(), $this->getInterfaceName());
    }
    public final function getMaybeNamespacedInterfaceName() : string
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        return $vars['namespace-types-and-interfaces'] ? $this->getNamespacedInterfaceName() : $this->getInterfaceName();
    }
    public function getSchemaInterfaceDescription() : ?string
    {
        return null;
    }
    /**
     * The fieldResolver will determine if it has a version or not, however the signature
     * of the fields comes from the interface. Only if there's a version will fieldArg "versionConstraint"
     * be added to the field. Hence, the interface must always say it has a version.
     * This will make fieldArg "versionConstraint" be always added to fields implementing an interface,
     * even if they do not have a version. However, the other way around, to say `false`,
     * would not allow any field implementing an interface to be versioned. So this way is better.
     *
     * @param string $fieldName
     * @return boolean
     */
    protected function hasSchemaFieldVersion(string $fieldName) : bool
    {
        return \true;
    }
    // public function getSchemaInterfaceVersion(string $fieldName): ?string
    // {
    //     return null;
    // }
    /**
     * This function is not called by the engine, to generate the schema.
     * Instead, the resolver is obtained from the fieldResolver.
     * To make sure that all fieldResolvers implementing the same interface
     * return the expected type for the field, they can obtain it from the
     * interface through this function.
     */
    public function getFieldTypeResolverClass(string $fieldName) : ?string
    {
        return null;
    }
}
