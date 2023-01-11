<?php

declare (strict_types=1);
namespace PoPCMSSchema\Posts\Hooks;

use PoPCMSSchema\CustomPosts\Hooks\AbstractAddDefaultCustomPostTypeModuleConfigurationHookSet;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
class PostAddDefaultCustomPostTypeModuleConfigurationHookSet extends AbstractAddDefaultCustomPostTypeModuleConfigurationHookSet
{
    /**
     * @var \PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface|null
     */
    private $postTypeAPI;
    /**
     * @param \PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface $postTypeAPI
     */
    public final function setPostTypeAPI($postTypeAPI) : void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    protected final function getPostTypeAPI() : PostTypeAPIInterface
    {
        /** @var PostTypeAPIInterface */
        return $this->postTypeAPI = $this->postTypeAPI ?? $this->instanceManager->getInstance(PostTypeAPIInterface::class);
    }
    protected function getCustomPostType() : string
    {
        return $this->getPostTypeAPI()->getPostCustomPostType();
    }
}
