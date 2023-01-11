<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput;
use PoPCMSSchema\Users\FilterInputs\EmailOrEmailsFilterInput;
use PoPCMSSchema\Users\Module;
use PoPCMSSchema\Users\ModuleConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
class UserSearchByInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver|null
     */
    private $emailScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput|null
     */
    private $searchFilterInput;
    /**
     * @var \PoPCMSSchema\Users\FilterInputs\EmailOrEmailsFilterInput|null
     */
    private $emailOrEmailsFilterInput;
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
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput $searchFilterInput
     */
    public final function setSearchFilterInput($searchFilterInput) : void
    {
        $this->searchFilterInput = $searchFilterInput;
    }
    protected final function getSearchFilterInput() : SearchFilterInput
    {
        /** @var SearchFilterInput */
        return $this->searchFilterInput = $this->searchFilterInput ?? $this->instanceManager->getInstance(SearchFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Users\FilterInputs\EmailOrEmailsFilterInput $emailOrEmailsFilterInput
     */
    public final function setEmailOrEmailsFilterInput($emailOrEmailsFilterInput) : void
    {
        $this->emailOrEmailsFilterInput = $emailOrEmailsFilterInput;
    }
    protected final function getEmailOrEmailsFilterInput() : EmailOrEmailsFilterInput
    {
        /** @var EmailOrEmailsFilterInput */
        return $this->emailOrEmailsFilterInput = $this->emailOrEmailsFilterInput ?? $this->instanceManager->getInstance(EmailOrEmailsFilterInput::class);
    }
    public function getTypeName() : string
    {
        return 'UserSearchByInput';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Oneof input to specify the property and data to search users', 'users');
    }
    protected function isOneInputValueMandatory() : bool
    {
        return \false;
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return ['name' => $this->getStringScalarTypeResolver(), 'emails' => $this->getEmailScalarTypeResolver()];
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
            $adminInputFieldNames[] = 'emails';
        }
        return $adminInputFieldNames;
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'name':
                return $this->__('Search by name', 'users');
            case 'emails':
                return $this->__('Search by email(s)', 'users');
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
            case 'emails':
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getInputFieldTypeModifiers($inputFieldName);
        }
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldName) : ?FilterInputInterface
    {
        switch ($inputFieldName) {
            case 'name':
                return $this->getSearchFilterInput();
            case 'emails':
                return $this->getEmailOrEmailsFilterInput();
            default:
                return parent::getInputFieldFilterInput($inputFieldName);
        }
    }
}
