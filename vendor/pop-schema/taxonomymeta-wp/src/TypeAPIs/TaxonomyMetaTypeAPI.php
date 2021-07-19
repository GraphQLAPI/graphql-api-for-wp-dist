<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMetaWP\TypeAPIs;

use PoPSchema\TaxonomyMeta\TypeAPIs\AbstractTaxonomyMetaTypeAPI;

class TaxonomyMetaTypeAPI extends AbstractTaxonomyMetaTypeAPI
{
    /**
     * @param string|int $termID
     * @return mixed
     */
    public function doGetTaxonomyMeta($termID, string $key, bool $single = false)
    {
        return \get_term_meta($termID, $key, $single);
    }
}
