<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoPCMSSchema\Users\TypeResolvers\InterfaceType\WithAuthorInterfaceTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
class WithAuthorInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver|null
     */
    private $userObjectTypeResolver;
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
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo() : array
    {
        return [WithAuthorInterfaceTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToImplement() : array
    {
        return ['author'];
    }
    /**
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($fieldName) : int
    {
        switch ($fieldName) {
            case 'author':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getFieldTypeModifiers($fieldName);
    }
    /**
     * @param string $fieldName
     */
    public function getFieldDescription($fieldName) : ?string
    {
        switch ($fieldName) {
            case 'author':
                return $this->__('The entity\'s author', 'queriedobject');
            default:
                return parent::getFieldDescription($fieldName);
        }
    }
    /**
     * @param string $fieldName
     */
    public function getFieldTypeResolver($fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'author':
                return $this->getUserObjectTypeResolver();
        }
        return parent::getFieldTypeResolver($fieldName);
    }
}
