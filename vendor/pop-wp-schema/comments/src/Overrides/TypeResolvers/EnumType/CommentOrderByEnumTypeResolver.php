<?php

declare(strict_types=1);

namespace PoPWPSchema\Comments\Overrides\TypeResolvers\EnumType;

use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentOrderByEnumTypeResolver as UpstreamCommentOrderByEnumTypeResolver;
use PoPWPSchema\Comments\Constants\CommentOrderBy;

class CommentOrderByEnumTypeResolver extends UpstreamCommentOrderByEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentOrderByEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_merge(
            parent::getEnumValues(),
            [
                CommentOrderBy::AUTHOR_EMAIL,
                CommentOrderBy::AUTHOR_IP,
                CommentOrderBy::AUTHOR_URL,
                CommentOrderBy::KARMA,
                CommentOrderBy::NONE,
            ]
        );
    }

    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue): ?string
    {
        switch ($enumValue) {
            case CommentOrderBy::AUTHOR_EMAIL:
                return $this->__('Order by author email', 'comments');
            case CommentOrderBy::AUTHOR_IP:
                return $this->__('Order by author IP', 'comments');
            case CommentOrderBy::AUTHOR_URL:
                return $this->__('Order by author URL', 'comments');
            case CommentOrderBy::KARMA:
                return $this->__('Order by karma', 'comments');
            case CommentOrderBy::NONE:
                return $this->__('Skip ordering', 'comments');
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
