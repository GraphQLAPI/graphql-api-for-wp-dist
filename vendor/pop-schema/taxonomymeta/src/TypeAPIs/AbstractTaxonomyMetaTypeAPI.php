<?php

declare (strict_types=1);
namespace PoPSchema\TaxonomyMeta\TypeAPIs;

use PoPSchema\TaxonomyMeta\ComponentConfiguration;
use PoPSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;
use PoPSchema\SchemaCommons\Facades\Services\AllowOrDenySettingsServiceFacade;
abstract class AbstractTaxonomyMetaTypeAPI implements TaxonomyMetaTypeAPIInterface
{
    /**
     * @param string|int $termID
     * @return mixed
     */
    public final function getTaxonomyTermMeta($termID, string $key, bool $single = \false)
    {
        /**
         * Check if the allow/denylist validation fails
         * Compare for full match or regex
         */
        $entries = ComponentConfiguration::getTaxonomyMetaEntries();
        $behavior = ComponentConfiguration::getTaxonomyMetaBehavior();
        $allowOrDenySettingsService = AllowOrDenySettingsServiceFacade::getInstance();
        if (!$allowOrDenySettingsService->isEntryAllowed($key, $entries, $behavior)) {
            return null;
        }
        return $this->doGetTaxonomyMeta($termID, $key, $single);
    }
    /**
     * @param string|int $termID
     * @return mixed
     */
    protected abstract function doGetTaxonomyMeta($termID, string $key, bool $single = \false);
}
