<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader|null
     */
    private $loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
    /**
     * @param \PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader
     */
    public final function setLoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader($loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader) : void
    {
        $this->loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader;
    }
    protected final function getLoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader() : LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader
    {
        /** @var LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader */
        return $this->loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader = $this->loggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader ?? $this->instanceManager->getInstance(LoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'LoggedInUserHasNoEditingCustomPostCapabilityErrorPayload';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to edit custom posts"', 'customposts');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoEditingCustomPostCapabilityErrorPayloadObjectTypeDataLoader();
    }
}
