<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\UserStateMutations\Constants\MutationInputProperties;
class LoginCredentialsInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    public final function setStringScalarTypeResolver($stringScalarTypeResolver) : void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected final function getStringScalarTypeResolver() : StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    public function getTypeName() : string
    {
        return 'LoginCredentialsInput';
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return [MutationInputProperties::USERNAME_OR_EMAIL => $this->getStringScalarTypeResolver(), MutationInputProperties::PASSWORD => $this->getStringScalarTypeResolver()];
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case MutationInputProperties::USERNAME_OR_EMAIL:
                return $this->__('The username or email', 'user-state-mutations');
            case MutationInputProperties::PASSWORD:
                return $this->__('The password', 'user-state-mutations');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldTypeModifiers($inputFieldName) : int
    {
        switch ($inputFieldName) {
            case MutationInputProperties::USERNAME_OR_EMAIL:
            case MutationInputProperties::PASSWORD:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getInputFieldTypeModifiers($inputFieldName);
        }
    }
}
