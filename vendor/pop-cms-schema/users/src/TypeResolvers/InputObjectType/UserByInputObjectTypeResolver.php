<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoPCMSSchema\SchemaCommons\FilterInputs\IncludeFilterInput;
use PoPCMSSchema\Users\FilterInputs\EmailFilterInput;
use PoPCMSSchema\Users\FilterInputs\UsernameFilterInput;
use PoPCMSSchema\Users\Module;
use PoPCMSSchema\Users\ModuleConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
class UserByInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver|null
     */
    private $emailScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\IncludeFilterInput|null
     */
    private $includeFilterInput;
    /**
     * @var \PoPCMSSchema\Users\FilterInputs\UsernameFilterInput|null
     */
    private $usernameFilterInput;
    /**
     * @var \PoPCMSSchema\Users\FilterInputs\EmailFilterInput|null
     */
    private $emailFilterInput;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver $idScalarTypeResolver
     */
    public final function setIDScalarTypeResolver($idScalarTypeResolver) : void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    protected final function getIDScalarTypeResolver() : IDScalarTypeResolver
    {
        /** @var IDScalarTypeResolver */
        return $this->idScalarTypeResolver = $this->idScalarTypeResolver ?? $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
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
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver $emailScalarTypeResolver
     */
    public final function setEmailScalarTypeResolver($emailScalarTypeResolver) : void
    {
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
    }
    protected final function getEmailScalarTypeResolver() : EmailScalarTypeResolver
    {
        /** @var EmailScalarTypeResolver */
        return $this->emailScalarTypeResolver = $this->emailScalarTypeResolver ?? $this->instanceManager->getInstance(EmailScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\IncludeFilterInput $includeFilterInput
     */
    public final function setIncludeFilterInput($includeFilterInput) : void
    {
        $this->includeFilterInput = $includeFilterInput;
    }
    protected final function getIncludeFilterInput() : IncludeFilterInput
    {
        /** @var IncludeFilterInput */
        return $this->includeFilterInput = $this->includeFilterInput ?? $this->instanceManager->getInstance(IncludeFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Users\FilterInputs\UsernameFilterInput $usernameFilterInput
     */
    public final function setUsernameFilterInput($usernameFilterInput) : void
    {
        $this->usernameFilterInput = $usernameFilterInput;
    }
    protected final function getUsernameFilterInput() : UsernameFilterInput
    {
        /** @var UsernameFilterInput */
        return $this->usernameFilterInput = $this->usernameFilterInput ?? $this->instanceManager->getInstance(UsernameFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Users\FilterInputs\EmailFilterInput $emailFilterInput
     */
    public final function setEmailFilterInput($emailFilterInput) : void
    {
        $this->emailFilterInput = $emailFilterInput;
    }
    protected final function getEmailFilterInput() : EmailFilterInput
    {
        /** @var EmailFilterInput */
        return $this->emailFilterInput = $this->emailFilterInput ?? $this->instanceManager->getInstance(EmailFilterInput::class);
    }
    public function getTypeName() : string
    {
        return 'UserByInput';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Oneof input to specify the property and data to fetch a user', 'users');
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return ['id' => $this->getIDScalarTypeResolver(), 'username' => $this->getStringScalarTypeResolver(), 'email' => $this->getEmailScalarTypeResolver()];
    }
    /**
     * @return string[]
     */
    public function getSensitiveInputFieldNames() : array
    {
        $adminInputFieldNames = parent::getSensitiveInputFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatUserEmailAsSensitiveData()) {
            $adminInputFieldNames[] = 'email';
        }
        return $adminInputFieldNames;
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'id':
                return $this->__('Query by user ID', 'users');
            case 'username':
                return $this->__('Query by username', 'users');
            case 'email':
                return $this->__('Query by email', 'users');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldName) : ?FilterInputInterface
    {
        switch ($inputFieldName) {
            case 'id':
                return $this->getIncludeFilterInput();
            case 'username':
                return $this->getUsernameFilterInput();
            case 'email':
                return $this->getEmailFilterInput();
            default:
                return parent::getInputFieldFilterInput($inputFieldName);
        }
    }
}
