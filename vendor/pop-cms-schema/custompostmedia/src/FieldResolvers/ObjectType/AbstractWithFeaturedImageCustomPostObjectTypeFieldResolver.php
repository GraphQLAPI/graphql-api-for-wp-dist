<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMedia\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMedia\FieldResolvers\InterfaceType\WithFeaturedImageInterfaceTypeFieldResolver;
use PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface;
abstract class AbstractWithFeaturedImageCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use \PoPCMSSchema\CustomPostMedia\FieldResolvers\ObjectType\MaybeWithFeaturedImageCustomPostObjectTypeFieldResolverTrait;
    /**
     * @var \PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface|null
     */
    private $customPostMediaTypeAPI;
    /**
     * @var \PoPCMSSchema\CustomPostMedia\FieldResolvers\InterfaceType\WithFeaturedImageInterfaceTypeFieldResolver|null
     */
    private $withFeaturedImageInterfaceTypeFieldResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface $customPostMediaTypeAPI
     */
    public final function setCustomPostMediaTypeAPI($customPostMediaTypeAPI) : void
    {
        $this->customPostMediaTypeAPI = $customPostMediaTypeAPI;
    }
    protected final function getCustomPostMediaTypeAPI() : CustomPostMediaTypeAPIInterface
    {
        /** @var CustomPostMediaTypeAPIInterface */
        return $this->customPostMediaTypeAPI = $this->customPostMediaTypeAPI ?? $this->instanceManager->getInstance(CustomPostMediaTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPostMedia\FieldResolvers\InterfaceType\WithFeaturedImageInterfaceTypeFieldResolver $withFeaturedImageInterfaceTypeFieldResolver
     */
    public final function setWithFeaturedImageInterfaceTypeFieldResolver($withFeaturedImageInterfaceTypeFieldResolver) : void
    {
        $this->withFeaturedImageInterfaceTypeFieldResolver = $withFeaturedImageInterfaceTypeFieldResolver;
    }
    protected final function getWithFeaturedImageInterfaceTypeFieldResolver() : WithFeaturedImageInterfaceTypeFieldResolver
    {
        /** @var WithFeaturedImageInterfaceTypeFieldResolver */
        return $this->withFeaturedImageInterfaceTypeFieldResolver = $this->withFeaturedImageInterfaceTypeFieldResolver ?? $this->instanceManager->getInstance(WithFeaturedImageInterfaceTypeFieldResolver::class);
    }
    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers() : array
    {
        return [$this->getWithFeaturedImageInterfaceTypeFieldResolver()];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['hasFeaturedImage', 'featuredImage'];
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
            case 'hasFeaturedImage':
                return $this->getCustomPostMediaTypeAPI()->hasCustomPostThumbnail($customPost);
            case 'featuredImage':
                return $this->getCustomPostMediaTypeAPI()->getCustomPostThumbnailID($customPost);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
