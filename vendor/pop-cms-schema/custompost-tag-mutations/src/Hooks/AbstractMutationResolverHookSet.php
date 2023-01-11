<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostTagMutations\Hooks;

use PoPCMSSchema\CustomPostMutations\Constants\HookNames;
use PoPCMSSchema\CustomPostTagMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
abstract class AbstractMutationResolverHookSet extends AbstractHookSet
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
    protected function init() : void
    {
        App::addAction(HookNames::EXECUTE_CREATE_OR_UPDATE, \Closure::fromCallable([$this, 'maybeSetTags']), 10, 2);
    }
    /**
     * @param int|string $customPostID
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    public function maybeSetTags($customPostID, $fieldDataAccessor) : void
    {
        // Only for that specific CPT
        if ($this->getCustomPostTypeAPI()->getCustomPostType($customPostID) !== $this->getCustomPostType()) {
            return;
        }
        if (!$fieldDataAccessor->hasValue(MutationInputProperties::TAGS)) {
            return;
        }
        $customPostTags = $fieldDataAccessor->getValue(MutationInputProperties::TAGS);
        $customPostTagTypeMutationAPI = $this->getCustomPostTagTypeMutationAPI();
        $customPostTagTypeMutationAPI->setTags($customPostID, $customPostTags, \false);
    }
    protected abstract function getCustomPostType() : string;
    protected abstract function getCustomPostTagTypeMutationAPI() : CustomPostTagTypeMutationAPIInterface;
}
