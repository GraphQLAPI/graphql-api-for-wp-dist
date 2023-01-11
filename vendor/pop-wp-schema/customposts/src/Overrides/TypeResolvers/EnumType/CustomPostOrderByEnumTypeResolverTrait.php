<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Overrides\TypeResolvers\EnumType;

use PoPWPSchema\CustomPosts\Constants\CustomPostOrderBy;

/**
 * The "order by" parameters are defined here:
 *
 * @see https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters
 */
trait CustomPostOrderByEnumTypeResolverTrait
{
    /**
     * @return string[]
     */
    public function getAdditionalCustomPostEnumStringValues(): array
    {
        return [
            CustomPostOrderBy::NONE,
            CustomPostOrderBy::COMMENT_COUNT,
            CustomPostOrderBy::RANDOM,
            CustomPostOrderBy::MODIFIED_DATE,
            CustomPostOrderBy::RELEVANCE,
            CustomPostOrderBy::TYPE,
            CustomPostOrderBy::PARENT,
            CustomPostOrderBy::MENU_ORDER,
            // CustomPostOrderBy::POST__IN,
            // CustomPostOrderBy::POST_PARENT__IN,
        ];
    }

    /**
     * @param string $enumValue
     */
    public function getAdditionalCustomPostEnumStringValueDescription($enumValue): ?string
    {
        switch ($enumValue) {
            case CustomPostOrderBy::NONE:
                return $this->getTranslationAPI()->__('Skip ordering', 'customposts');
            case CustomPostOrderBy::COMMENT_COUNT:
                return $this->getTranslationAPI()->__('Order by number of comments', 'customposts');
            case CustomPostOrderBy::RANDOM:
                return $this->getTranslationAPI()->__('Order by a random number', 'customposts');
            case CustomPostOrderBy::MODIFIED_DATE:
                return $this->getTranslationAPI()->__('Order by last modified date', 'customposts');
            case CustomPostOrderBy::RELEVANCE:
                return $this->getTranslationAPI()->__('Order by relevance', 'customposts');
            case CustomPostOrderBy::TYPE:
                return $this->getTranslationAPI()->__('Order by type', 'customposts');
            case CustomPostOrderBy::PARENT:
                return $this->getTranslationAPI()->__('Order by custom post parent id', 'customposts');
            case CustomPostOrderBy::MENU_ORDER:
                return $this->getTranslationAPI()->__('Order by menu order', 'customposts');
            default:
                return null;
        }
    }
}
