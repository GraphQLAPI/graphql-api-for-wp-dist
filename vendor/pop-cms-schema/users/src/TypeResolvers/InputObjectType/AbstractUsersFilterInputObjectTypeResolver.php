<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;
abstract class AbstractUsersFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserSearchByInputObjectTypeResolver|null
     */
    private $userSearchByInputObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserSearchByInputObjectTypeResolver $userSearchByInputObjectTypeResolver
     */
    public final function setUserSearchByInputObjectTypeResolver($userSearchByInputObjectTypeResolver) : void
    {
        $this->userSearchByInputObjectTypeResolver = $userSearchByInputObjectTypeResolver;
    }
    protected final function getUserSearchByInputObjectTypeResolver() : \PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserSearchByInputObjectTypeResolver
    {
        /** @var UserSearchByInputObjectTypeResolver */
        return $this->userSearchByInputObjectTypeResolver = $this->userSearchByInputObjectTypeResolver ?? $this->instanceManager->getInstance(\PoPCMSSchema\Users\TypeResolvers\InputObjectType\UserSearchByInputObjectTypeResolver::class);
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Input to filter users', 'users');
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge(parent::getInputFieldNameTypeResolvers(), ['searchBy' => $this->getUserSearchByInputObjectTypeResolver()]);
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'searchBy':
                return $this->__('Search for users', 'users');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
}
