<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsNotLoggedInErrorPayload;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\UserIsNotLoggedInErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractUserIsNotLoggedInErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\UserIsNotLoggedInErrorPayloadObjectTypeResolver|null
     */
    private $userIsNotLoggedInErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\UserIsNotLoggedInErrorPayloadObjectTypeResolver $userIsNotLoggedInErrorPayloadObjectTypeResolver
     */
    public final function setUserIsNotLoggedInErrorPayloadObjectTypeResolver($userIsNotLoggedInErrorPayloadObjectTypeResolver) : void
    {
        $this->userIsNotLoggedInErrorPayloadObjectTypeResolver = $userIsNotLoggedInErrorPayloadObjectTypeResolver;
    }
    protected final function getUserIsNotLoggedInErrorPayloadObjectTypeResolver() : UserIsNotLoggedInErrorPayloadObjectTypeResolver
    {
        /** @var UserIsNotLoggedInErrorPayloadObjectTypeResolver */
        return $this->userIsNotLoggedInErrorPayloadObjectTypeResolver = $this->userIsNotLoggedInErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(UserIsNotLoggedInErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getUserIsNotLoggedInErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return UserIsNotLoggedInErrorPayload::class;
    }
}
