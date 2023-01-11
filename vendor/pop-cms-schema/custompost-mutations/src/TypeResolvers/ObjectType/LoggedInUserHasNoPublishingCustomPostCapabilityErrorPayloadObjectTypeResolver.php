<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader|null
     */
    private $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
    /**
     * @param \PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader
     */
    public final function setLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader($loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader) : void
    {
        $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
    }
    protected final function getLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader() : LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader
    {
        /** @var LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader */
        return $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader = $this->loggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader ?? $this->instanceManager->getInstance(LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'LoggedInUserHasNoPublishingCustomPostCapabilityErrorPayload';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to publish custom posts"', 'customposts');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoPublishingCustomPostCapabilityErrorPayloadObjectTypeDataLoader();
    }
}
