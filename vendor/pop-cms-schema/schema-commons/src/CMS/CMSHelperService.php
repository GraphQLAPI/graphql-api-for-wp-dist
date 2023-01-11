<?php

declare (strict_types=1);
namespace PoPCMSSchema\SchemaCommons\CMS;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Services\BasicServiceTrait;
class CMSHelperService implements \PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface|null
     */
    private $cmsService;
    /**
     * @param \PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface $cmsService
     */
    public final function setCMSService($cmsService) : void
    {
        $this->cmsService = $cmsService;
    }
    protected final function getCMSService() : \PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface
    {
        /** @var CMSServiceInterface */
        return $this->cmsService = $this->cmsService ?? $this->instanceManager->getInstance(\PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface::class);
    }
    /**
     * @param string $url
     */
    public function getLocalURLPath($url) : ?string
    {
        if (\strncmp($url, $this->getCMSService()->getHomeURL(), \strlen($this->getCMSService()->getHomeURL())) === 0) {
            return GeneralUtils::getPath($url);
        }
        return null;
    }
}
