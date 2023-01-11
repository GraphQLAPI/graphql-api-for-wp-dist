<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractOneofMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
class LoginUserOneofMutationResolver extends AbstractOneofMutationResolver
{
    /**
     * @var \PoPCMSSchema\UserStateMutations\MutationResolvers\LoginUserByCredentialsMutationResolver|null
     */
    private $loginUserByCredentialsMutationResolver;
    /**
     * @param \PoPCMSSchema\UserStateMutations\MutationResolvers\LoginUserByCredentialsMutationResolver $loginUserByCredentialsMutationResolver
     */
    public final function setLoginUserByCredentialsMutationResolver($loginUserByCredentialsMutationResolver) : void
    {
        $this->loginUserByCredentialsMutationResolver = $loginUserByCredentialsMutationResolver;
    }
    protected final function getLoginUserByCredentialsMutationResolver() : \PoPCMSSchema\UserStateMutations\MutationResolvers\LoginUserByCredentialsMutationResolver
    {
        /** @var LoginUserByCredentialsMutationResolver */
        return $this->loginUserByCredentialsMutationResolver = $this->loginUserByCredentialsMutationResolver ?? $this->instanceManager->getInstance(\PoPCMSSchema\UserStateMutations\MutationResolvers\LoginUserByCredentialsMutationResolver::class);
    }
    /**
     * @return array<string,MutationResolverInterface>
     */
    protected function getInputFieldNameMutationResolvers() : array
    {
        return ['credentials' => $this->getLoginUserByCredentialsMutationResolver()];
    }
}
