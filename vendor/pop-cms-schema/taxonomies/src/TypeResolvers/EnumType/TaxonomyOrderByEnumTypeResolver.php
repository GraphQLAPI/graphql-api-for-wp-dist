<?php

declare (strict_types=1);
namespace PoPCMSSchema\Taxonomies\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\Taxonomies\Constants\TaxonomyOrderBy;
class TaxonomyOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName() : string
    {
        return 'TaxonomyOrderByEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues() : array
    {
        return [TaxonomyOrderBy::NAME, TaxonomyOrderBy::SLUG, TaxonomyOrderBy::ID, TaxonomyOrderBy::DESCRIPTION, TaxonomyOrderBy::PARENT, TaxonomyOrderBy::COUNT, TaxonomyOrderBy::NONE, TaxonomyOrderBy::INCLUDE, TaxonomyOrderBy::SLUG__IN];
    }
    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue) : ?string
    {
        switch ($enumValue) {
            case TaxonomyOrderBy::NAME:
                return $this->__('Order by name', 'taxonomies');
            case TaxonomyOrderBy::SLUG:
                return $this->__('Order by slug', 'taxonomies');
            case TaxonomyOrderBy::ID:
                return $this->__('Order by ID', 'taxonomies');
            case TaxonomyOrderBy::DESCRIPTION:
                return $this->__('Order by description', 'taxonomies');
            case TaxonomyOrderBy::PARENT:
                return $this->__('Order by parent', 'taxonomies');
            case TaxonomyOrderBy::COUNT:
                return $this->__('Order by number of objects associated with the term', 'taxonomies');
            case TaxonomyOrderBy::NONE:
                return $this->__('Order by none, i.e. omit the ordering', 'taxonomies');
            case TaxonomyOrderBy::INCLUDE:
                return $this->__('Match the \'order\' of the $include param', 'taxonomies');
            case TaxonomyOrderBy::SLUG__IN:
                return $this->__('Match the \'order\' of the $slug param', 'taxonomies');
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
