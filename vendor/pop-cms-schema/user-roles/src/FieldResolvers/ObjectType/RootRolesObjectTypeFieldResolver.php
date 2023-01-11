<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserRoles\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
class RootRolesObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use \PoPCMSSchema\UserRoles\FieldResolvers\ObjectType\RolesObjectTypeFieldResolverTrait;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface|null
     */
    private $userRoleTypeAPI;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    public final function setStringScalarTypeResolver($stringScalarTypeResolver) : void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected final function getStringScalarTypeResolver() : StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface $userRoleTypeAPI
     */
    public final function setUserRoleTypeAPI($userRoleTypeAPI) : void
    {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }
    protected final function getUserRoleTypeAPI() : UserRoleTypeAPIInterface
    {
        /** @var UserRoleTypeAPIInterface */
        return $this->userRoleTypeAPI = $this->userRoleTypeAPI ?? $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [RootObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case 'roles':
            case 'capabilities':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
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
            case 'roles':
                return $this->getUserRoleTypeAPI()->getRoleNames();
            case 'capabilities':
                return $this->getUserRoleTypeAPI()->getCapabilities();
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
