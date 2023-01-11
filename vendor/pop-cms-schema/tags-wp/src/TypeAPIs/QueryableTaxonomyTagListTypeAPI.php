<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\TypeAPIs;

use PoPCMSSchema\TagsWP\StandaloneTypeAPIs\InjectableTaxonomyTagTypeAPI;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTaxonomyTagListTypeAPIInterface;

class QueryableTaxonomyTagListTypeAPI implements QueryableTaxonomyTagListTypeAPIInterface
{
    /**
     * @var array<string,InjectableTaxonomyTagTypeAPI>
     */
    private $injectableTaxonomyTagTypeAPIs = [];

    /**
     * @param string $catTaxonomy
     */
    protected function getInjectableTaxonomyTagTypeAPI($catTaxonomy): InjectableTaxonomyTagTypeAPI
    {
        if (!isset($this->injectableTaxonomyTagTypeAPIs[$catTaxonomy])) {
            $this->injectableTaxonomyTagTypeAPIs[$catTaxonomy] = new InjectableTaxonomyTagTypeAPI($catTaxonomy);
        }
        return $this->injectableTaxonomyTagTypeAPIs[$catTaxonomy];
    }

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @param string $catTaxonomy
     */
    public function getTaxonomyTags($catTaxonomy, $query, $options = []): array
    {
        return $this->getInjectableTaxonomyTagTypeAPI($catTaxonomy)->getTags($query, $options);
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @param string $catTaxonomy
     */
    public function getTaxonomyTagCount($catTaxonomy, $query, $options = []): int
    {
        return $this->getInjectableTaxonomyTagTypeAPI($catTaxonomy)->getTagCount($query, $options);
    }
}
