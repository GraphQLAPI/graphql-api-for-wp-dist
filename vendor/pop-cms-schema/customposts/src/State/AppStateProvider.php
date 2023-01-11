<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPosts\Routing\RequestNature;
class AppStateProvider extends AbstractAppStateProvider
{
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface|null
     */
    private $customPostTypeAPI;
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface $customPostTypeAPI
     */
    public final function setCustomPostTypeAPI($customPostTypeAPI) : void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    protected final function getCustomPostTypeAPI() : CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI = $this->customPostTypeAPI ?? $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    /**
     * @param array<string,mixed> $state
     */
    public function augment(&$state) : void
    {
        $nature = $state['nature'];
        $state['routing']['is-custompost'] = $nature === RequestNature::CUSTOMPOST;
        // Attributes needed to match the ComponentRoutingProcessor vars conditions
        if ($nature === RequestNature::CUSTOMPOST) {
            $customPostID = $state['routing']['queried-object-id'];
            $state['routing']['queried-object-post-type'] = $this->getCustomPostTypeAPI()->getCustomPostType($customPostID);
        }
    }
}
