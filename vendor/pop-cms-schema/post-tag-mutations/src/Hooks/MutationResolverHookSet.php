<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTagMutations\Hooks;

use PoPCMSSchema\CustomPostTagMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPCMSSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;
class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    /**
     * @var \PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface|null
     */
    private $postTypeAPI;
    /**
     * @var \PoPCMSSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface|null
     */
    private $postTagTypeMutationAPI;
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
    /**
     * @param \PoPCMSSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface $postTagTypeMutationAPI
     */
    public final function setPostTagTypeMutationAPI($postTagTypeMutationAPI) : void
    {
        $this->postTagTypeMutationAPI = $postTagTypeMutationAPI;
    }
    protected final function getPostTagTypeMutationAPI() : PostTagTypeMutationAPIInterface
    {
        /** @var PostTagTypeMutationAPIInterface */
        return $this->postTagTypeMutationAPI = $this->postTagTypeMutationAPI ?? $this->instanceManager->getInstance(PostTagTypeMutationAPIInterface::class);
    }
    protected function getCustomPostType() : string
    {
        return $this->getPostTypeAPI()->getPostCustomPostType();
    }
    protected function getCustomPostTagTypeMutationAPI() : CustomPostTagTypeMutationAPIInterface
    {
        return $this->getPostTagTypeMutationAPI();
    }
}
