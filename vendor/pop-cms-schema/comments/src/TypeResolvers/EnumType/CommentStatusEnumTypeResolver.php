<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\Comments\Constants\CommentStatus;
class CommentStatusEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName() : string
    {
        return 'CommentStatusEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues() : array
    {
        return [CommentStatus::APPROVE, CommentStatus::HOLD, CommentStatus::SPAM, CommentStatus::TRASH];
    }
    /**
     * Description for a specific enum value
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue) : ?string
    {
        switch ($enumValue) {
            case CommentStatus::APPROVE:
                return $this->__('Approved comment', 'comments');
            case CommentStatus::HOLD:
                return $this->__('Onhold comment', 'comments');
            case CommentStatus::SPAM:
                return $this->__('Spam comment', 'comments');
            case CommentStatus::TRASH:
                return $this->__('Trashed comment', 'comments');
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
