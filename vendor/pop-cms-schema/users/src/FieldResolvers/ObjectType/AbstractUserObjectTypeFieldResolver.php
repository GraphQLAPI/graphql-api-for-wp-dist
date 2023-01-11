<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserPaginationInputObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\UsersFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserSortInputObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
abstract class AbstractUserObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;
    /**
     * @var \PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface|null
     */
    private $userTypeAPI;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver|null
     */
    private $userObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Users\TypeResolvers\InputObjectType\UsersFilterInputObjectTypeResolver|null
     */
    private $usersFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserPaginationInputObjectTypeResolver|null
     */
    private $userPaginationInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserSortInputObjectTypeResolver|null
     */
    private $userSortInputObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface $userTypeAPI
     */
    public final function setUserTypeAPI($userTypeAPI) : void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    protected final function getUserTypeAPI() : UserTypeAPIInterface
    {
        /** @var UserTypeAPIInterface */
        return $this->userTypeAPI = $this->userTypeAPI ?? $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver $intScalarTypeResolver
     */
    public final function setIntScalarTypeResolver($intScalarTypeResolver) : void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    protected final function getIntScalarTypeResolver() : IntScalarTypeResolver
    {
        /** @var IntScalarTypeResolver */
        return $this->intScalarTypeResolver = $this->intScalarTypeResolver ?? $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver $userObjectTypeResolver
     */
    public final function setUserObjectTypeResolver($userObjectTypeResolver) : void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    protected final function getUserObjectTypeResolver() : UserObjectTypeResolver
    {
        /** @var UserObjectTypeResolver */
        return $this->userObjectTypeResolver = $this->userObjectTypeResolver ?? $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Users\TypeResolvers\InputObjectType\UsersFilterInputObjectTypeResolver $usersFilterInputObjectTypeResolver
     */
    public final function setUsersFilterInputObjectTypeResolver($usersFilterInputObjectTypeResolver) : void
    {
        $this->usersFilterInputObjectTypeResolver = $usersFilterInputObjectTypeResolver;
    }
    protected final function getUsersFilterInputObjectTypeResolver() : UsersFilterInputObjectTypeResolver
    {
        /** @var UsersFilterInputObjectTypeResolver */
        return $this->usersFilterInputObjectTypeResolver = $this->usersFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(UsersFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserPaginationInputObjectTypeResolver $userPaginationInputObjectTypeResolver
     */
    public final function setUserPaginationInputObjectTypeResolver($userPaginationInputObjectTypeResolver) : void
    {
        $this->userPaginationInputObjectTypeResolver = $userPaginationInputObjectTypeResolver;
    }
    protected final function getUserPaginationInputObjectTypeResolver() : UserPaginationInputObjectTypeResolver
    {
        /** @var UserPaginationInputObjectTypeResolver */
        return $this->userPaginationInputObjectTypeResolver = $this->userPaginationInputObjectTypeResolver ?? $this->instanceManager->getInstance(UserPaginationInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserSortInputObjectTypeResolver $userSortInputObjectTypeResolver
     */
    public final function setUserSortInputObjectTypeResolver($userSortInputObjectTypeResolver) : void
    {
        $this->userSortInputObjectTypeResolver = $userSortInputObjectTypeResolver;
    }
    protected final function getUserSortInputObjectTypeResolver() : UserSortInputObjectTypeResolver
    {
        /** @var UserSortInputObjectTypeResolver */
        return $this->userSortInputObjectTypeResolver = $this->userSortInputObjectTypeResolver ?? $this->instanceManager->getInstance(UserSortInputObjectTypeResolver::class);
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['users', 'userCount'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'users':
                return $this->getUserObjectTypeResolver();
            case 'userCount':
                return $this->getIntScalarTypeResolver();
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
            case 'userCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'users':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
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
            case 'users':
                return $this->__('Users', 'pop-users');
            case 'userCount':
                return $this->__('Number of users', 'pop-users');
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
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'users':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getUsersFilterInputObjectTypeResolver(), 'pagination' => $this->getUserPaginationInputObjectTypeResolver(), 'sort' => $this->getUserSortInputObjectTypeResolver()]);
            case 'userCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getUsersFilterInputObjectTypeResolver()]);
            default:
                return $fieldArgNameTypeResolvers;
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
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'users':
                return $this->getUserTypeAPI()->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'userCount':
                return $this->getUserTypeAPI()->getUserCount($query);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
