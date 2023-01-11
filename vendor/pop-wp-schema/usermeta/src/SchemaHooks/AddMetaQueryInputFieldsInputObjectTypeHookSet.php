<?php

declare(strict_types=1);

namespace PoPWPSchema\UserMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\AbstractUsersFilterInputObjectTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;
use PoPWPSchema\UserMeta\TypeResolvers\InputObjectType\UserMetaQueryInputObjectTypeResolver;

class AddMetaQueryInputFieldsInputObjectTypeHookSet extends AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet
{
    /**
     * @var \PoPWPSchema\UserMeta\TypeResolvers\InputObjectType\UserMetaQueryInputObjectTypeResolver|null
     */
    private $userMetaQueryInputObjectTypeResolver;

    /**
     * @param \PoPWPSchema\UserMeta\TypeResolvers\InputObjectType\UserMetaQueryInputObjectTypeResolver $userMetaQueryInputObjectTypeResolver
     */
    final public function setUserMetaQueryInputObjectTypeResolver($userMetaQueryInputObjectTypeResolver): void
    {
        $this->userMetaQueryInputObjectTypeResolver = $userMetaQueryInputObjectTypeResolver;
    }
    final protected function getUserMetaQueryInputObjectTypeResolver(): UserMetaQueryInputObjectTypeResolver
    {
        /** @var UserMetaQueryInputObjectTypeResolver */
        return $this->userMetaQueryInputObjectTypeResolver = $this->userMetaQueryInputObjectTypeResolver ?? $this->instanceManager->getInstance(UserMetaQueryInputObjectTypeResolver::class);
    }

    protected function getMetaQueryInputObjectTypeResolver(): AbstractMetaQueryInputObjectTypeResolver
    {
        return $this->getUserMetaQueryInputObjectTypeResolver();
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    protected function isInputObjectTypeResolver($inputObjectTypeResolver): bool
    {
        return $inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver;
    }
}
