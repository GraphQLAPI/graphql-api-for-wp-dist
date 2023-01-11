<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectModels\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver|null
     */
    private $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver
     */
    public final function setLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver($loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver) : void
    {
        $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver;
    }
    protected final function getLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver() : LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver
    {
        /** @var LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver */
        return $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver = $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload::class;
    }
}
