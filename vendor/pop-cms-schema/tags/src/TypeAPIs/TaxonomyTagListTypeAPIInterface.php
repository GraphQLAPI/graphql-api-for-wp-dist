<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\TypeAPIs;

interface TaxonomyTagListTypeAPIInterface
{
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @param string $catTaxonomy
     */
    public function getTaxonomyTags($catTaxonomy, $query, $options = []) : array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @param string $catTaxonomy
     */
    public function getTaxonomyTagCount($catTaxonomy, $query, $options = []) : int;
}
