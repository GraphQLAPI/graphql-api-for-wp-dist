<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\TypeAPIs;

interface TaxonomyCategoryListTypeAPIInterface
{
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @param string $catTaxonomy
     */
    public function getTaxonomyCategories($catTaxonomy, $query, $options = []) : array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @param string $catTaxonomy
     */
    public function getTaxonomyCategoryCount($catTaxonomy, $query, $options = []) : int;
}
