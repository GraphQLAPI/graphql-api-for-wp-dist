<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\Comments\Constants\CommentOrderBy;
class CommentOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName() : string
    {
        return 'CommentOrderByEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues() : array
    {
        return [CommentOrderBy::ID, CommentOrderBy::DATE, CommentOrderBy::CONTENT, CommentOrderBy::PARENT, CommentOrderBy::CUSTOM_POST, CommentOrderBy::TYPE, CommentOrderBy::STATUS];
    }
    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue) : ?string
    {
        switch ($enumValue) {
            case CommentOrderBy::ID:
                return $this->__('Order by ID', 'comments');
            case CommentOrderBy::DATE:
                return $this->__('Order by date', 'comments');
            case CommentOrderBy::CONTENT:
                return $this->__('Order by content', 'comments');
            case CommentOrderBy::PARENT:
                return $this->__('Order by parent comment', 'comments');
            case CommentOrderBy::CUSTOM_POST:
                return $this->__('Order by ID of the custom post', 'comments');
            case CommentOrderBy::TYPE:
                return $this->__('Order by type', 'comments');
            case CommentOrderBy::STATUS:
                return $this->__('Order by status (approved or not)', 'comments');
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
