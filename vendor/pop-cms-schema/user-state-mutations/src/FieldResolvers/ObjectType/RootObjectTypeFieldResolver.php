<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\UserStateMutations\Module;
use PoPCMSSchema\UserStateMutations\ModuleConfiguration;
use PoPCMSSchema\UserStateMutations\MutationResolvers\LoginUserOneofMutationResolver;
use PoPCMSSchema\UserStateMutations\MutationResolvers\LogoutUserMutationResolver;
use PoPCMSSchema\UserStateMutations\Constants\MutationInputProperties;
use PoPCMSSchema\UserStateMutations\MutationResolvers\PayloadableLoginUserOneofMutationResolver;
use PoPCMSSchema\UserStateMutations\MutationResolvers\PayloadableLogoutUserMutationResolver;
use PoPCMSSchema\UserStateMutations\TypeResolvers\InputObjectType\LoginUserByOneofInputObjectTypeResolver;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\RootLoginUserMutationPayloadObjectTypeResolver;
use PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\RootLogoutUserMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver|null
     */
    private $userObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\UserStateMutations\MutationResolvers\LoginUserOneofMutationResolver|null
     */
    private $loginUserOneofMutationResolver;
    /**
     * @var \PoPCMSSchema\UserStateMutations\MutationResolvers\LogoutUserMutationResolver|null
     */
    private $logoutUserMutationResolver;
    /**
     * @var \PoPCMSSchema\UserStateMutations\TypeResolvers\InputObjectType\LoginUserByOneofInputObjectTypeResolver|null
     */
    private $loginUserByOneofInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\UserStateMutations\MutationResolvers\PayloadableLoginUserOneofMutationResolver|null
     */
    private $payloadableLoginUserOneofMutationResolver;
    /**
     * @var \PoPCMSSchema\UserStateMutations\MutationResolvers\PayloadableLogoutUserMutationResolver|null
     */
    private $payloadableLogoutUserMutationResolver;
    /**
     * @var \PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\RootLoginUserMutationPayloadObjectTypeResolver|null
     */
    private $rootLoginUserMutationPayloadObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\RootLogoutUserMutationPayloadObjectTypeResolver|null
     */
    private $rootLogoutUserMutationPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver $userObjectTypeResolver
     */
    public final function setUserObjectTypeResolver($userObjectTypeResolver) : void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    protected final function getUserObjectTypeResolver() : UserObjectTypeResolver
    {
        /** @var UserObjectTypeResolver */
        return $this->userObjectTypeResolver = $this->userObjectTypeResolver ?? $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\UserStateMutations\MutationResolvers\LoginUserOneofMutationResolver $loginUserOneofMutationResolver
     */
    public final function setLoginUserOneofMutationResolver($loginUserOneofMutationResolver) : void
    {
        $this->loginUserOneofMutationResolver = $loginUserOneofMutationResolver;
    }
    protected final function getLoginUserOneofMutationResolver() : LoginUserOneofMutationResolver
    {
        /** @var LoginUserOneofMutationResolver */
        return $this->loginUserOneofMutationResolver = $this->loginUserOneofMutationResolver ?? $this->instanceManager->getInstance(LoginUserOneofMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\UserStateMutations\MutationResolvers\LogoutUserMutationResolver $logoutUserMutationResolver
     */
    public final function setLogoutUserMutationResolver($logoutUserMutationResolver) : void
    {
        $this->logoutUserMutationResolver = $logoutUserMutationResolver;
    }
    protected final function getLogoutUserMutationResolver() : LogoutUserMutationResolver
    {
        /** @var LogoutUserMutationResolver */
        return $this->logoutUserMutationResolver = $this->logoutUserMutationResolver ?? $this->instanceManager->getInstance(LogoutUserMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\UserStateMutations\TypeResolvers\InputObjectType\LoginUserByOneofInputObjectTypeResolver $loginUserByOneofInputObjectTypeResolver
     */
    public final function setLoginUserByOneofInputObjectTypeResolver($loginUserByOneofInputObjectTypeResolver) : void
    {
        $this->loginUserByOneofInputObjectTypeResolver = $loginUserByOneofInputObjectTypeResolver;
    }
    protected final function getLoginUserByOneofInputObjectTypeResolver() : LoginUserByOneofInputObjectTypeResolver
    {
        /** @var LoginUserByOneofInputObjectTypeResolver */
        return $this->loginUserByOneofInputObjectTypeResolver = $this->loginUserByOneofInputObjectTypeResolver ?? $this->instanceManager->getInstance(LoginUserByOneofInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\UserStateMutations\MutationResolvers\PayloadableLoginUserOneofMutationResolver $payloadableLoginUserOneofMutationResolver
     */
    public final function setPayloadableLoginUserOneofMutationResolver($payloadableLoginUserOneofMutationResolver) : void
    {
        $this->payloadableLoginUserOneofMutationResolver = $payloadableLoginUserOneofMutationResolver;
    }
    protected final function getPayloadableLoginUserOneofMutationResolver() : PayloadableLoginUserOneofMutationResolver
    {
        /** @var PayloadableLoginUserOneofMutationResolver */
        return $this->payloadableLoginUserOneofMutationResolver = $this->payloadableLoginUserOneofMutationResolver ?? $this->instanceManager->getInstance(PayloadableLoginUserOneofMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\UserStateMutations\MutationResolvers\PayloadableLogoutUserMutationResolver $payloadableLogoutUserMutationResolver
     */
    public final function setPayloadableLogoutUserMutationResolver($payloadableLogoutUserMutationResolver) : void
    {
        $this->payloadableLogoutUserMutationResolver = $payloadableLogoutUserMutationResolver;
    }
    protected final function getPayloadableLogoutUserMutationResolver() : PayloadableLogoutUserMutationResolver
    {
        /** @var PayloadableLogoutUserMutationResolver */
        return $this->payloadableLogoutUserMutationResolver = $this->payloadableLogoutUserMutationResolver ?? $this->instanceManager->getInstance(PayloadableLogoutUserMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\RootLoginUserMutationPayloadObjectTypeResolver $rootLoginUserMutationPayloadObjectTypeResolver
     */
    public final function setRootLoginUserMutationPayloadObjectTypeResolver($rootLoginUserMutationPayloadObjectTypeResolver) : void
    {
        $this->rootLoginUserMutationPayloadObjectTypeResolver = $rootLoginUserMutationPayloadObjectTypeResolver;
    }
    protected final function getRootLoginUserMutationPayloadObjectTypeResolver() : RootLoginUserMutationPayloadObjectTypeResolver
    {
        /** @var RootLoginUserMutationPayloadObjectTypeResolver */
        return $this->rootLoginUserMutationPayloadObjectTypeResolver = $this->rootLoginUserMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(RootLoginUserMutationPayloadObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\UserStateMutations\TypeResolvers\ObjectType\RootLogoutUserMutationPayloadObjectTypeResolver $rootLogoutUserMutationPayloadObjectTypeResolver
     */
    public final function setRootLogoutUserMutationPayloadObjectTypeResolver($rootLogoutUserMutationPayloadObjectTypeResolver) : void
    {
        $this->rootLogoutUserMutationPayloadObjectTypeResolver = $rootLogoutUserMutationPayloadObjectTypeResolver;
    }
    protected final function getRootLogoutUserMutationPayloadObjectTypeResolver() : RootLogoutUserMutationPayloadObjectTypeResolver
    {
        /** @var RootLogoutUserMutationPayloadObjectTypeResolver */
        return $this->rootLogoutUserMutationPayloadObjectTypeResolver = $this->rootLogoutUserMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(RootLogoutUserMutationPayloadObjectTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [RootObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['loginUser', 'logoutUser'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'loginUser':
                return $this->__('Log the user in', 'user-state-mutations');
            case 'logoutUser':
                return $this->__('Log the user out', 'user-state-mutations');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserStateMutations = $moduleConfiguration->usePayloadableUserStateMutations();
        if (!$usePayloadableUserStateMutations) {
            return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        switch ($fieldName) {
            case 'loginUser':
            case 'logoutUser':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        switch ($fieldName) {
            case 'loginUser':
                return [MutationInputProperties::BY => $this->getLoginUserByOneofInputObjectTypeResolver()];
            default:
                return parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['loginUser' => MutationInputProperties::BY]:
                return $this->__('Choose which credentials to use to log-in, and provide them', 'user-state-mutations');
            default:
                return parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['loginUser' => MutationInputProperties::BY]:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldMutationResolver($objectTypeResolver, $fieldName) : ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserStateMutations = $moduleConfiguration->usePayloadableUserStateMutations();
        switch ($fieldName) {
            case 'loginUser':
                return $usePayloadableUserStateMutations ? $this->getPayloadableLoginUserOneofMutationResolver() : $this->getLoginUserOneofMutationResolver();
            case 'logoutUser':
                return $usePayloadableUserStateMutations ? $this->getPayloadableLogoutUserMutationResolver() : $this->getLogoutUserMutationResolver();
            default:
                return parent::getFieldMutationResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableUserStateMutations = $moduleConfiguration->usePayloadableUserStateMutations();
        if ($usePayloadableUserStateMutations) {
            switch ($fieldName) {
                case 'loginUser':
                    return $this->getRootLoginUserMutationPayloadObjectTypeResolver();
                case 'logoutUser':
                    return $this->getRootLogoutUserMutationPayloadObjectTypeResolver();
                default:
                    return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
            }
        }
        switch ($fieldName) {
            case 'loginUser':
            case 'logoutUser':
                return $this->getUserObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
}
