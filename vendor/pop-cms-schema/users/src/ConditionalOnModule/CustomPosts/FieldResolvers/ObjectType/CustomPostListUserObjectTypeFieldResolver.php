<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType\AbstractCustomPostListObjectTypeFieldResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeResolvers\InputObjectType\UserCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
class CustomPostListUserObjectTypeFieldResolver extends AbstractCustomPostListObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeResolvers\InputObjectType\UserCustomPostsFilterInputObjectTypeResolver|null
     */
    private $userCustomPostsFilterInputObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeResolvers\InputObjectType\UserCustomPostsFilterInputObjectTypeResolver $userCustomPostsFilterInputObjectTypeResolver
     */
    public final function setUserCustomPostsFilterInputObjectTypeResolver($userCustomPostsFilterInputObjectTypeResolver) : void
    {
        $this->userCustomPostsFilterInputObjectTypeResolver = $userCustomPostsFilterInputObjectTypeResolver;
    }
    protected final function getUserCustomPostsFilterInputObjectTypeResolver() : UserCustomPostsFilterInputObjectTypeResolver
    {
        /** @var UserCustomPostsFilterInputObjectTypeResolver */
        return $this->userCustomPostsFilterInputObjectTypeResolver = $this->userCustomPostsFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(UserCustomPostsFilterInputObjectTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [UserObjectTypeResolver::class];
    }
    protected function getCustomPostsFilterInputObjectTypeResolver() : AbstractCustomPostsFilterInputObjectTypeResolver
    {
        return $this->getUserCustomPostsFilterInputObjectTypeResolver();
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'customPosts':
                return $this->__('Custom posts by the user', 'pop-users');
            case 'customPostCount':
                return $this->__('Number of custom posts by the user', 'pop-users');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,mixed>
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function getQuery($objectTypeResolver, $object, $fieldDataAccessor) : array
    {
        $query = parent::getQuery($objectTypeResolver, $object, $fieldDataAccessor);
        $user = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'customPosts':
            case 'customPostCount':
                $query['authors'] = [$objectTypeResolver->getID($user)];
                break;
        }
        return $query;
    }
}
