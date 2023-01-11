<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\Component\Component;
interface QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface extends \PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldSchemaDefinitionResolverInterface
{
    /**
     * @param string $fieldName
     */
    public function getFieldFilterInputContainerComponent($fieldName) : ?Component;
}
