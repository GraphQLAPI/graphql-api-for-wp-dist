<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;
abstract class AbstractCustomPostTypeAPI implements \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface|null
     */
    private $cmsHelperService;
    /**
     * @param \PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface $cmsHelperService
     */
    public final function setCMSHelperService($cmsHelperService) : void
    {
        $this->cmsHelperService = $cmsHelperService;
    }
    protected final function getCMSHelperService() : CMSHelperServiceInterface
    {
        /** @var CMSHelperServiceInterface */
        return $this->cmsHelperService = $this->cmsHelperService ?? $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
    }
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getPermalinkPath($customPostObjectOrID) : ?string
    {
        $permalink = $this->getPermalink($customPostObjectOrID);
        if ($permalink === null) {
            return null;
        }
        return $this->getCMSHelperService()->getLocalURLPath($permalink);
    }
}
