<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
class CustomPostStatusEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName() : string
    {
        return 'CustomPostStatusEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues() : array
    {
        return [CustomPostStatus::PUBLISH, CustomPostStatus::PENDING, CustomPostStatus::DRAFT, CustomPostStatus::TRASH];
    }
    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue) : ?string
    {
        switch ($enumValue) {
            case CustomPostStatus::PUBLISH:
                return $this->__('Published content', 'customposts');
            case CustomPostStatus::PENDING:
                return $this->__('Pending content', 'customposts');
            case CustomPostStatus::DRAFT:
                return $this->__('Draft content', 'customposts');
            case CustomPostStatus::TRASH:
                return $this->__('Trashed content', 'customposts');
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
