<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserStateMutations\ObjectModels\InvalidUserEmailErrorPayload;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\InvalidUserEmailErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractInvalidUserEmailErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\InvalidUserEmailErrorPayloadObjectTypeResolver|null
     */
    private $userIsNotLoggedInErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\InvalidUserEmailErrorPayloadObjectTypeResolver $userIsNotLoggedInErrorPayloadObjectTypeResolver
     */
    public final function setInvalidUserEmailErrorPayloadObjectTypeResolver($userIsNotLoggedInErrorPayloadObjectTypeResolver) : void
    {
        $this->userIsNotLoggedInErrorPayloadObjectTypeResolver = $userIsNotLoggedInErrorPayloadObjectTypeResolver;
    }
    protected final function getInvalidUserEmailErrorPayloadObjectTypeResolver() : InvalidUserEmailErrorPayloadObjectTypeResolver
    {
        /** @var InvalidUserEmailErrorPayloadObjectTypeResolver */
        return $this->userIsNotLoggedInErrorPayloadObjectTypeResolver = $this->userIsNotLoggedInErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(InvalidUserEmailErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getInvalidUserEmailErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return InvalidUserEmailErrorPayload::class;
    }
}
