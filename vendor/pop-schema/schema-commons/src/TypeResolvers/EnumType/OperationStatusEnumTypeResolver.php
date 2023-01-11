<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\TypeResolvers\EnumType;

use PoPSchema\SchemaCommons\Enums\OperationStatusEnum;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
class OperationStatusEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName() : string
    {
        return 'OperationStatusEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues() : array
    {
        return [OperationStatusEnum::SUCCESS, OperationStatusEnum::FAILURE];
    }
    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue) : ?string
    {
        switch ($enumValue) {
            case OperationStatusEnum::SUCCESS:
                return $this->__('Success', 'schema-commons');
            case OperationStatusEnum::FAILURE:
                return $this->__('Failure', 'schema-commons');
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
