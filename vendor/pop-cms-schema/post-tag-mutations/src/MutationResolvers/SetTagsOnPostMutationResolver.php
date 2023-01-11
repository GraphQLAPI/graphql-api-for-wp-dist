<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTagMutations\MutationResolvers;

use PoPCMSSchema\CustomPostTagMutations\MutationResolvers\AbstractSetTagsOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;
class SetTagsOnPostMutationResolver extends AbstractSetTagsOnCustomPostMutationResolver
{
    /**
     * @var \PoPCMSSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface|null
     */
    private $postCategoryTypeMutationAPIInterface;
    /**
     * @param \PoPCMSSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface $postCategoryTypeMutationAPIInterface
     */
    public final function setPostTagTypeMutationAPI($postCategoryTypeMutationAPIInterface) : void
    {
        $this->postCategoryTypeMutationAPIInterface = $postCategoryTypeMutationAPIInterface;
    }
    protected final function getPostTagTypeMutationAPI() : PostTagTypeMutationAPIInterface
    {
        /** @var PostTagTypeMutationAPIInterface */
        return $this->postCategoryTypeMutationAPIInterface = $this->postCategoryTypeMutationAPIInterface ?? $this->instanceManager->getInstance(PostTagTypeMutationAPIInterface::class);
    }
    protected function getCustomPostTagTypeMutationAPI() : CustomPostTagTypeMutationAPIInterface
    {
        return $this->getPostTagTypeMutationAPI();
    }
    protected function getEntityName() : string
    {
        return $this->__('post', 'post-tag-mutations');
    }
}
