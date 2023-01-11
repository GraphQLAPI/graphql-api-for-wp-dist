<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserStateMutations\ObjectModels\PasswordIsIncorrectErrorPayload;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\PasswordIsIncorrectErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractPasswordIsIncorrectErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\PasswordIsIncorrectErrorPayloadObjectTypeResolver|null
     */
    private $userIsNotLoggedInErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\PasswordIsIncorrectErrorPayloadObjectTypeResolver $userIsNotLoggedInErrorPayloadObjectTypeResolver
     */
    public final function setPasswordIsIncorrectErrorPayloadObjectTypeResolver($userIsNotLoggedInErrorPayloadObjectTypeResolver) : void
    {
        $this->userIsNotLoggedInErrorPayloadObjectTypeResolver = $userIsNotLoggedInErrorPayloadObjectTypeResolver;
    }
    protected final function getPasswordIsIncorrectErrorPayloadObjectTypeResolver() : PasswordIsIncorrectErrorPayloadObjectTypeResolver
    {
        /** @var PasswordIsIncorrectErrorPayloadObjectTypeResolver */
        return $this->userIsNotLoggedInErrorPayloadObjectTypeResolver = $this->userIsNotLoggedInErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(PasswordIsIncorrectErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getPasswordIsIncorrectErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return PasswordIsIncorrectErrorPayload::class;
    }
}
