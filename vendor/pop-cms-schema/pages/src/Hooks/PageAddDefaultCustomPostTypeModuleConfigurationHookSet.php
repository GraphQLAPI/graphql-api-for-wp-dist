<?php

declare (strict_types=1);
namespace PoPCMSSchema\Pages\Hooks;

use PoPCMSSchema\CustomPosts\Hooks\AbstractAddDefaultCustomPostTypeModuleConfigurationHookSet;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
class PageAddDefaultCustomPostTypeModuleConfigurationHookSet extends AbstractAddDefaultCustomPostTypeModuleConfigurationHookSet
{
    /**
     * @var \PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface|null
     */
    private $pageTypeAPI;
    /**
     * @param \PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface $pageTypeAPI
     */
    public final function setPageTypeAPI($pageTypeAPI) : void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    protected final function getPageTypeAPI() : PageTypeAPIInterface
    {
        /** @var PageTypeAPIInterface */
        return $this->pageTypeAPI = $this->pageTypeAPI ?? $this->instanceManager->getInstance(PageTypeAPIInterface::class);
    }
    protected function getCustomPostType() : string
    {
        return $this->getPageTypeAPI()->getPageCustomPostType();
    }
}
