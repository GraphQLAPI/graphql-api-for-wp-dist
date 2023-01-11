<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\StandaloneTypeAPIs;

use PoPCMSSchema\CategoriesWP\TypeAPIs\AbstractCategoryTypeAPI;

final class InjectableTaxonomyCategoryTypeAPI extends AbstractCategoryTypeAPI
{
    /**
     * @var string
     */
    protected $catTaxonomy;
    public function __construct(string $catTaxonomy)
    {
        $this->catTaxonomy = $catTaxonomy;
    }
    protected function getCategoryTaxonomyName(): string
    {
        return $this->catTaxonomy;
    }
}
