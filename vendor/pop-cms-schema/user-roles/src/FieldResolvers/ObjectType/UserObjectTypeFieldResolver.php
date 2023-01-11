<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserRoles\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\Root\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\UserRoles\Module;
use PoPCMSSchema\UserRoles\ModuleConfiguration;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
class UserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
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
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver $booleanScalarTypeResolver
     */
    public final function setBooleanScalarTypeResolver($booleanScalarTypeResolver) : void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    protected final function getBooleanScalarTypeResolver() : BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver = $this->booleanScalarTypeResolver ?? $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
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
        return [UserObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['roles', 'capabilities', 'hasRole', 'hasAnyRole', 'hasCapability', 'hasAnyCapability'];
    }
    /**
     * @return string[]
     */
    public function getSensitiveFieldNames() : array
    {
        $sensitiveFieldNames = parent::getSensitiveFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatUserRoleAsSensitiveData()) {
            $sensitiveFieldNames[] = 'roles';
            $sensitiveFieldNames[] = 'hasRole';
            $sensitiveFieldNames[] = 'hasAnyRole';
        }
        if ($moduleConfiguration->treatUserCapabilityAsSensitiveData()) {
            $sensitiveFieldNames[] = 'capabilities';
            $sensitiveFieldNames[] = 'hasCapability';
            $sensitiveFieldNames[] = 'hasAnyCapability';
        }
        return $sensitiveFieldNames;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'roles':
                return $this->getStringScalarTypeResolver();
            case 'capabilities':
                return $this->getStringScalarTypeResolver();
            case 'hasRole':
                return $this->getBooleanScalarTypeResolver();
            case 'hasAnyRole':
                return $this->getBooleanScalarTypeResolver();
            case 'hasCapability':
                return $this->getBooleanScalarTypeResolver();
            case 'hasAnyCapability':
                return $this->getBooleanScalarTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case 'roles':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            case 'capabilities':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY;
            case 'hasRole':
            case 'hasAnyRole':
            case 'hasCapability':
            case 'hasAnyCapability':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'roles':
                return $this->__('User roles', 'user-roles');
            case 'capabilities':
                return $this->__('User capabilities', 'user-roles');
            case 'hasRole':
                return $this->__('Does the user have a specific role?', 'user-roles');
            case 'hasAnyRole':
                return $this->__('Does the user have any role from a provided list?', 'user-roles');
            case 'hasCapability':
                return $this->__('Does the user have a specific capability?', 'user-roles');
            case 'hasAnyCapability':
                return $this->__('Does the user have any capability from a provided list?', 'user-roles');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        switch ($fieldName) {
            case 'hasRole':
                return ['role' => $this->getStringScalarTypeResolver()];
            case 'hasAnyRole':
                return ['roles' => $this->getStringScalarTypeResolver()];
            case 'hasCapability':
                return ['capability' => $this->getStringScalarTypeResolver()];
            case 'hasAnyCapability':
                return ['capabilities' => $this->getStringScalarTypeResolver()];
            default:
                return parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['hasRole' => 'role']:
                return $this->__('User role to check against', 'user-roles');
            case ['hasAnyRole' => 'roles']:
                return $this->__('User roles to check against', 'user-roles');
            case ['hasCapability' => 'capability']:
                return $this->__('User capability to check against', 'user-roles');
            case ['hasAnyCapability' => 'capabilities']:
                return $this->__('User capabilities to check against', 'user-roles');
            default:
                return parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['hasRole' => 'role']:
            case ['hasCapability' => 'capability']:
                return SchemaTypeModifiers::MANDATORY;
            case ['hasAnyRole' => 'roles']:
            case ['hasAnyCapability' => 'capabilities']:
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY | SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
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
        $user = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'roles':
                return $this->getUserRoleTypeAPI()->getUserRoles($user);
            case 'capabilities':
                return $this->getUserRoleTypeAPI()->getUserCapabilities($user);
            case 'hasRole':
                $userRoles = $this->getUserRoleTypeAPI()->getUserRoles($user);
                return \in_array($fieldDataAccessor->getValue('role'), $userRoles);
            case 'hasAnyRole':
                $userRoles = $this->getUserRoleTypeAPI()->getUserRoles($user);
                return !empty(\array_intersect($fieldDataAccessor->getValue('roles'), $userRoles));
            case 'hasCapability':
                $userCapabilities = $this->getUserRoleTypeAPI()->getUserCapabilities($user);
                return \in_array($fieldDataAccessor->getValue('capability'), $userCapabilities);
            case 'hasAnyCapability':
                $userCapabilities = $this->getUserRoleTypeAPI()->getUserCapabilities($user);
                return !empty(\array_intersect($fieldDataAccessor->getValue('capabilities'), $userCapabilities));
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
