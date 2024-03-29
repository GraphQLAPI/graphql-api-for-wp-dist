<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Taxonomies;

interface TaxonomyInterface
{
    public function getTaxonomy(): string;

    /**
     * @param bool $titleCase
     */
    public function getTaxonomyName($titleCase = true): string;

    /**
     * Taxonomy plural name
     *
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    public function getTaxonomyPluralNames($titleCase = true): string;

    /**
     * @return string[]
     */
    public function getCustomPostTypes(): array;

    public function isHierarchical(): bool;
}
