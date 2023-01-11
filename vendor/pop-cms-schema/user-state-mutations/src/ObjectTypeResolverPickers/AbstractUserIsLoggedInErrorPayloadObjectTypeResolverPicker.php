<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserStateMutations\ObjectModels\UserIsLoggedInErrorPayload;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\UserIsLoggedInErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractUserIsLoggedInErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\UserIsLoggedInErrorPayloadObjectTypeResolver|null
     */
    private $userIsLoggedInErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\UserIsLoggedInErrorPayloadObjectTypeResolver $userIsLoggedInErrorPayloadObjectTypeResolver
     */
    public final function setUserIsLoggedInErrorPayloadObjectTypeResolver($userIsLoggedInErrorPayloadObjectTypeResolver) : void
    {
        $this->userIsLoggedInErrorPayloadObjectTypeResolver = $userIsLoggedInErrorPayloadObjectTypeResolver;
    }
    protected final function getUserIsLoggedInErrorPayloadObjectTypeResolver() : UserIsLoggedInErrorPayloadObjectTypeResolver
    {
        /** @var UserIsLoggedInErrorPayloadObjectTypeResolver */
        return $this->userIsLoggedInErrorPayloadObjectTypeResolver = $this->userIsLoggedInErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(UserIsLoggedInErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getUserIsLoggedInErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return UserIsLoggedInErrorPayload::class;
    }
}
