<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;
abstract class AbstractUserTypeAPI implements \PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface
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
     * @param string|int|object $userObjectOrID
     */
    public function getUserURLPath($userObjectOrID) : ?string
    {
        $userURL = $this->getUserURL($userObjectOrID);
        if ($userURL === null) {
            return null;
        }
        return $this->getCMSHelperService()->getLocalURLPath($userURL);
    }
}
