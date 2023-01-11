<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoPermissionToEditCustomPostErrorPayload;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver|null
     */
    private $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver
     */
    public final function setLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver($loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver) : void
    {
        $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver = $loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver;
    }
    protected final function getLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver() : LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver
    {
        /** @var LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver */
        return $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver = $this->loggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(LoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPermissionToEditCustomPostErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return LoggedInUserHasNoPermissionToEditCustomPostErrorPayload::class;
    }
}
