<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\ObjectType\InvalidUsernameErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class InvalidUsernameErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\ObjectType\InvalidUsernameErrorPayloadObjectTypeDataLoader|null
     */
    private $invalidUsernameErrorPayloadObjectTypeDataLoader;
    /**
     * @param \PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\ObjectType\InvalidUsernameErrorPayloadObjectTypeDataLoader $invalidUsernameErrorPayloadObjectTypeDataLoader
     */
    public final function setInvalidUsernameErrorPayloadObjectTypeDataLoader($invalidUsernameErrorPayloadObjectTypeDataLoader) : void
    {
        $this->invalidUsernameErrorPayloadObjectTypeDataLoader = $invalidUsernameErrorPayloadObjectTypeDataLoader;
    }
    protected final function getInvalidUsernameErrorPayloadObjectTypeDataLoader() : InvalidUsernameErrorPayloadObjectTypeDataLoader
    {
        /** @var InvalidUsernameErrorPayloadObjectTypeDataLoader */
        return $this->invalidUsernameErrorPayloadObjectTypeDataLoader = $this->invalidUsernameErrorPayloadObjectTypeDataLoader ?? $this->instanceManager->getInstance(InvalidUsernameErrorPayloadObjectTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'InvalidUsernameErrorPayload';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Error payload for: "No user is registered with the provided username"', 'user-state-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getInvalidUsernameErrorPayloadObjectTypeDataLoader();
    }
}
