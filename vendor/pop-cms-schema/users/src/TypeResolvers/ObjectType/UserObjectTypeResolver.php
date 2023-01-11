<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Users\RelationalTypeDataLoaders\ObjectType\UserTypeDataLoader;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
class UserObjectTypeResolver extends AbstractObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface|null
     */
    private $userTypeAPI;
    /**
     * @var \PoPCMSSchema\Users\RelationalTypeDataLoaders\ObjectType\UserTypeDataLoader|null
     */
    private $userTypeDataLoader;
    /**
     * @param \PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface $userTypeAPI
     */
    public final function setUserTypeAPI($userTypeAPI) : void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    protected final function getUserTypeAPI() : UserTypeAPIInterface
    {
        /** @var UserTypeAPIInterface */
        return $this->userTypeAPI = $this->userTypeAPI ?? $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Users\RelationalTypeDataLoaders\ObjectType\UserTypeDataLoader $userTypeDataLoader
     */
    public final function setUserTypeDataLoader($userTypeDataLoader) : void
    {
        $this->userTypeDataLoader = $userTypeDataLoader;
    }
    protected final function getUserTypeDataLoader() : UserTypeDataLoader
    {
        /** @var UserTypeDataLoader */
        return $this->userTypeDataLoader = $this->userTypeDataLoader ?? $this->instanceManager->getInstance(UserTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'User';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Representation of a user', 'users');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        $user = $object;
        return $this->getUserTypeAPI()->getUserID($user);
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getUserTypeDataLoader();
    }
}
