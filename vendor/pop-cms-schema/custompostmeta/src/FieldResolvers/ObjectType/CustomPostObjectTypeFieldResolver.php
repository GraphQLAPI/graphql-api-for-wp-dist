<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
class CustomPostObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface|null
     */
    private $customPostMetaTypeAPI;
    /**
     * @param \PoPCMSSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface $customPostMetaTypeAPI
     */
    public final function setCustomPostMetaTypeAPI($customPostMetaTypeAPI) : void
    {
        $this->customPostMetaTypeAPI = $customPostMetaTypeAPI;
    }
    protected final function getCustomPostMetaTypeAPI() : CustomPostMetaTypeAPIInterface
    {
        /** @var CustomPostMetaTypeAPIInterface */
        return $this->customPostMetaTypeAPI = $this->customPostMetaTypeAPI ?? $this->instanceManager->getInstance(CustomPostMetaTypeAPIInterface::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [AbstractCustomPostObjectTypeResolver::class];
    }
    protected function getMetaTypeAPI() : MetaTypeAPIInterface
    {
        return $this->getCustomPostMetaTypeAPI();
    }
    /**
     * @return mixed
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        $customPost = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaValue':
            case 'metaValues':
                return $this->getCustomPostMetaTypeAPI()->getCustomPostMeta($customPost, $fieldDataAccessor->getValue('key'), $fieldDataAccessor->getFieldName() === 'metaValue');
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
