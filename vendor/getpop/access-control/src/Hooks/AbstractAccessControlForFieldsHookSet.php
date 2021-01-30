<?php

declare (strict_types=1);
namespace PoP\AccessControl\Hooks;

use PoP\Engine\Hooks\AbstractCMSBootHookSet;
use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
abstract class AbstractAccessControlForFieldsHookSet extends \PoP\Engine\Hooks\AbstractCMSBootHookSet
{
    /**
     * Indicate if this hook is enabled
     *
     * @return boolean
     */
    protected function enabled() : bool
    {
        return \true;
    }
    public function cmsBoot() : void
    {
        if (!$this->enabled()) {
            return;
        }
        // If no field defined => it applies to any field
        if ($fieldNames = $this->getFieldNames()) {
            foreach ($fieldNames as $fieldName) {
                $this->hooksAPI->addFilter(\PoP\ComponentModel\TypeResolvers\HookHelpers::getHookNameToFilterField($fieldName), array($this, 'maybeFilterFieldName'), 10, 5);
            }
        } else {
            $this->hooksAPI->addFilter(\PoP\ComponentModel\TypeResolvers\HookHelpers::getHookNameToFilterField(), array($this, 'maybeFilterFieldName'), 10, 5);
        }
    }
    public function maybeFilterFieldName(bool $include, \PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, \PoP\ComponentModel\FieldResolvers\FieldResolverInterface $fieldResolver, array $fieldInterfaceResolverClasses, string $fieldName) : bool
    {
        // Because there may be several hooks chained, if any of them has already rejected the field, then already return that response
        if (!$include) {
            return \false;
        }
        // Check if to remove the field
        return !$this->removeFieldName($typeResolver, $fieldResolver, $fieldInterfaceResolverClasses, $fieldName);
    }
    /**
     * Field names to remove
     *
     * @return array
     */
    protected abstract function getFieldNames() : array;
    /**
     * Decide if to remove the fieldNames
     *
     * @param TypeResolverInterface $typeResolver
     * @param FieldResolverInterface $fieldResolver
     * @param string $fieldName
     * @return boolean
     */
    protected function removeFieldName(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, \PoP\ComponentModel\FieldResolvers\FieldResolverInterface $fieldResolver, array $fieldInterfaceResolverClasses, string $fieldName) : bool
    {
        return \true;
    }
}
