<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface;
use PoPCMSSchema\Users\FieldResolvers\InterfaceType\WithAuthorInterfaceTypeFieldResolver;
class CustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface|null
     */
    private $customPostUserTypeAPI;
    /**
     * @var \PoPCMSSchema\Users\FieldResolvers\InterfaceType\WithAuthorInterfaceTypeFieldResolver|null
     */
    private $withAuthorInterfaceTypeFieldResolver;
    /**
     * @param \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface $customPostUserTypeAPI
     */
    public final function setCustomPostUserTypeAPI($customPostUserTypeAPI) : void
    {
        $this->customPostUserTypeAPI = $customPostUserTypeAPI;
    }
    protected final function getCustomPostUserTypeAPI() : CustomPostUserTypeAPIInterface
    {
        /** @var CustomPostUserTypeAPIInterface */
        return $this->customPostUserTypeAPI = $this->customPostUserTypeAPI ?? $this->instanceManager->getInstance(CustomPostUserTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Users\FieldResolvers\InterfaceType\WithAuthorInterfaceTypeFieldResolver $withAuthorInterfaceTypeFieldResolver
     */
    public final function setWithAuthorInterfaceTypeFieldResolver($withAuthorInterfaceTypeFieldResolver) : void
    {
        $this->withAuthorInterfaceTypeFieldResolver = $withAuthorInterfaceTypeFieldResolver;
    }
    protected final function getWithAuthorInterfaceTypeFieldResolver() : WithAuthorInterfaceTypeFieldResolver
    {
        /** @var WithAuthorInterfaceTypeFieldResolver */
        return $this->withAuthorInterfaceTypeFieldResolver = $this->withAuthorInterfaceTypeFieldResolver ?? $this->instanceManager->getInstance(WithAuthorInterfaceTypeFieldResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [AbstractCustomPostObjectTypeResolver::class];
    }
    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers() : array
    {
        return [$this->getWithAuthorInterfaceTypeFieldResolver()];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['author'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'author':
                return $this->__('The post\'s author', '');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
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
        switch ($fieldDataAccessor->getFieldName()) {
            case 'author':
                /** @var string|int */
                return $this->getCustomPostUserTypeAPI()->getAuthorID($object);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
