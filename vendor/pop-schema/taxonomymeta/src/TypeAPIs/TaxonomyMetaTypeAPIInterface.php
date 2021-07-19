<?php

declare (strict_types=1);
namespace PoPSchema\TaxonomyMeta\TypeAPIs;

interface TaxonomyMetaTypeAPIInterface
{
    /**
     * @param string|int $termID
     * @return mixed
     */
    public function getTaxonomyTermMeta($termID, string $key, bool $single = \false);
}
