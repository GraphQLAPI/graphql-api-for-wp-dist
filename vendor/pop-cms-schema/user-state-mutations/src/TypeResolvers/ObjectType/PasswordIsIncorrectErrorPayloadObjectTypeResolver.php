<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\ObjectType\PasswordIsIncorrectErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class PasswordIsIncorrectErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\ObjectType\PasswordIsIncorrectErrorPayloadObjectTypeDataLoader|null
     */
    private $passwordIsIncorrectErrorPayloadObjectTypeDataLoader;
    /**
     * @param \PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\ObjectType\PasswordIsIncorrectErrorPayloadObjectTypeDataLoader $passwordIsIncorrectErrorPayloadObjectTypeDataLoader
     */
    public final function setPasswordIsIncorrectErrorPayloadObjectTypeDataLoader($passwordIsIncorrectErrorPayloadObjectTypeDataLoader) : void
    {
        $this->passwordIsIncorrectErrorPayloadObjectTypeDataLoader = $passwordIsIncorrectErrorPayloadObjectTypeDataLoader;
    }
    protected final function getPasswordIsIncorrectErrorPayloadObjectTypeDataLoader() : PasswordIsIncorrectErrorPayloadObjectTypeDataLoader
    {
        /** @var PasswordIsIncorrectErrorPayloadObjectTypeDataLoader */
        return $this->passwordIsIncorrectErrorPayloadObjectTypeDataLoader = $this->passwordIsIncorrectErrorPayloadObjectTypeDataLoader ?? $this->instanceManager->getInstance(PasswordIsIncorrectErrorPayloadObjectTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'PasswordIsIncorrectErrorPayload';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Error payload for: "The password is incorrect"', 'user-state-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getPasswordIsIncorrectErrorPayloadObjectTypeDataLoader();
    }
}
