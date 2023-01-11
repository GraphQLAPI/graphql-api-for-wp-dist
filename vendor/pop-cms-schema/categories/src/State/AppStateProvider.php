<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPCMSSchema\Categories\Routing\RequestNature;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
class AppStateProvider extends AbstractAppStateProvider
{
    /**
     * @var \PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface|null
     */
    private $taxonomyTermTypeAPI;
    /**
     * @param \PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI
     */
    public final function setTaxonomyTermTypeAPI($taxonomyTermTypeAPI) : void
    {
        $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
    }
    protected final function getTaxonomyTermTypeAPI() : TaxonomyTermTypeAPIInterface
    {
        /** @var TaxonomyTermTypeAPIInterface */
        return $this->taxonomyTermTypeAPI = $this->taxonomyTermTypeAPI ?? $this->instanceManager->getInstance(TaxonomyTermTypeAPIInterface::class);
    }
    /**
     * @param array<string,mixed> $state
     */
    public function augment(&$state) : void
    {
        $nature = $state['nature'];
        $state['routing']['is-category'] = $nature === RequestNature::CATEGORY;
        // Save the name of the taxonomy as an attribute,
        // needed to match the ComponentRoutingProcessor vars conditions
        if ($nature === RequestNature::CATEGORY) {
            $termObject = $state['routing']['queried-object'];
            $state['routing']['taxonomy-name'] = $this->getTaxonomyTermTypeAPI()->getTermTaxonomyName($termObject);
        }
    }
}
