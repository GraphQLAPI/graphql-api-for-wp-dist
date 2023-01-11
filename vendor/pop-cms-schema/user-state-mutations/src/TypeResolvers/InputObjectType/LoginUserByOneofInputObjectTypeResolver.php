<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;
class LoginUserByOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\UserStateMutations\TypeResolvers\InputObjectType\LoginCredentialsInputObjectTypeResolver|null
     */
    private $loginCredentialsInputObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\UserStateMutations\TypeResolvers\InputObjectType\LoginCredentialsInputObjectTypeResolver $loginCredentialsInputObjectTypeResolver
     */
    public final function setLoginCredentialsInputObjectTypeResolver($loginCredentialsInputObjectTypeResolver) : void
    {
        $this->loginCredentialsInputObjectTypeResolver = $loginCredentialsInputObjectTypeResolver;
    }
    protected final function getLoginCredentialsInputObjectTypeResolver() : \PoPCMSSchema\UserStateMutations\TypeResolvers\InputObjectType\LoginCredentialsInputObjectTypeResolver
    {
        /** @var LoginCredentialsInputObjectTypeResolver */
        return $this->loginCredentialsInputObjectTypeResolver = $this->loginCredentialsInputObjectTypeResolver ?? $this->instanceManager->getInstance(\PoPCMSSchema\UserStateMutations\TypeResolvers\InputObjectType\LoginCredentialsInputObjectTypeResolver::class);
    }
    public function getTypeName() : string
    {
        return 'LoginUserByInput';
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return ['credentials' => $this->getLoginCredentialsInputObjectTypeResolver()];
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'credentials':
                return $this->__('Login using the website credentials', 'user-state-mutations');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
}
