<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\TypeAPIs;

use PoPCMSSchema\CategoriesWP\StandaloneTypeAPIs\InjectableTaxonomyCategoryTypeAPI;
use PoPCMSSchema\Categories\TypeAPIs\QueryableTaxonomyCategoryListTypeAPIInterface;

class QueryableTaxonomyCategoryListTypeAPI implements QueryableTaxonomyCategoryListTypeAPIInterface
{
    /**
     * @var array<string,InjectableTaxonomyCategoryTypeAPI>
     */
    private $injectableTaxonomyCategoryTypeAPIs = [];

    /**
     * @param string $catTaxonomy
     */
    protected function getInjectableTaxonomyCategoryTypeAPI($catTaxonomy): InjectableTaxonomyCategoryTypeAPI
    {
        if (!isset($this->injectableTaxonomyCategoryTypeAPIs[$catTaxonomy])) {
            $this->injectableTaxonomyCategoryTypeAPIs[$catTaxonomy] = new InjectableTaxonomyCategoryTypeAPI($catTaxonomy);
        }
        return $this->injectableTaxonomyCategoryTypeAPIs[$catTaxonomy];
    }

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @param string $catTaxonomy
     */
    public function getTaxonomyCategories($catTaxonomy, $query, $options = []): array
    {
        return $this->getInjectableTaxonomyCategoryTypeAPI($catTaxonomy)->getCategories($query, $options);
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @param string $catTaxonomy
     */
    public function getTaxonomyCategoryCount($catTaxonomy, $query, $options = []): int
    {
        return $this->getInjectableTaxonomyCategoryTypeAPI($catTaxonomy)->getCategoryCount($query, $options);
    }
}
