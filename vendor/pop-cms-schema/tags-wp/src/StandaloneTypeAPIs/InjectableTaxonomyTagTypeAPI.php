<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\StandaloneTypeAPIs;

use PoPCMSSchema\TagsWP\TypeAPIs\AbstractTagTypeAPI;

final class InjectableTaxonomyTagTypeAPI extends AbstractTagTypeAPI
{
    /**
     * @var string
     */
    protected $tagTaxonomy;
    public function __construct(string $tagTaxonomy)
    {
        $this->tagTaxonomy = $tagTaxonomy;
    }
    protected function getTagTaxonomyName(): string
    {
        return $this->tagTaxonomy;
    }
}
