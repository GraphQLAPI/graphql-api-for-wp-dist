<?php

declare (strict_types=1);
namespace PoPCMSSchema\SchemaCommons\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\SchemaCommons\Constants\Order;
class OrderEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName() : string
    {
        return 'OrderEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues() : array
    {
        return [Order::ASC, Order::DESC];
    }
    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue) : ?string
    {
        switch ($enumValue) {
            case Order::ASC:
                return $this->__('Ascending order', 'schema-commons');
            case Order::DESC:
                return $this->__('Descending order', 'schema-commons');
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
