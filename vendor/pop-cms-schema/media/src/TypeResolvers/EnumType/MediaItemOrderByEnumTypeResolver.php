<?php

declare (strict_types=1);
namespace PoPCMSSchema\Media\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\Media\Constants\MediaItemOrderBy;
class MediaItemOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName() : string
    {
        return 'MediaItemOrderByEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues() : array
    {
        return [MediaItemOrderBy::ID, MediaItemOrderBy::TITLE, MediaItemOrderBy::DATE];
    }
    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue) : ?string
    {
        switch ($enumValue) {
            case MediaItemOrderBy::ID:
                return $this->__('Order by ID', 'media');
            case MediaItemOrderBy::TITLE:
                return $this->__('Order by title', 'media');
            case MediaItemOrderBy::DATE:
                return $this->__('Order by date', 'media');
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
