<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Users\FilterInputs\EmailOrEmailsFilterInput;
use PoPCMSSchema\Users\FilterInputs\NameFilterInput;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public const COMPONENT_FILTERINPUT_NAME = 'filterinput-name';
    public const COMPONENT_FILTERINPUT_EMAILS = 'filterinput-emails';
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver|null
     */
    private $emailScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Users\FilterInputs\NameFilterInput|null
     */
    private $nameFilterInput;
    /**
     * @var \PoPCMSSchema\Users\FilterInputs\EmailOrEmailsFilterInput|null
     */
    private $emailOrEmailsFilterInput;
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
     * @param \PoPCMSSchema\Users\FilterInputs\NameFilterInput $nameFilterInput
     */
    public final function setNameFilterInput($nameFilterInput) : void
    {
        $this->nameFilterInput = $nameFilterInput;
    }
    protected final function getNameFilterInput() : NameFilterInput
    {
        /** @var NameFilterInput */
        return $this->nameFilterInput = $this->nameFilterInput ?? $this->instanceManager->getInstance(NameFilterInput::class);
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
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUT_NAME, self::COMPONENT_FILTERINPUT_EMAILS);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInput($component) : ?FilterInputInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_NAME:
                return $this->getNameFilterInput();
            case self::COMPONENT_FILTERINPUT_EMAILS:
                return $this->getEmailOrEmailsFilterInput();
            default:
                return null;
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getName($component) : string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_NAME:
            case self::COMPONENT_FILTERINPUT_EMAILS:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(self::COMPONENT_FILTERINPUT_NAME => 'nombre', self::COMPONENT_FILTERINPUT_EMAILS => 'emails');
                return $names[$component->name];
        }
        return parent::getName($component);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputTypeResolver($component) : InputTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_NAME:
                return $this->getStringScalarTypeResolver();
            case self::COMPONENT_FILTERINPUT_EMAILS:
                return $this->getEmailScalarTypeResolver();
            default:
                return $this->getDefaultSchemaFilterInputTypeResolver();
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputTypeModifiers($component) : int
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_EMAILS:
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return SchemaTypeModifiers::NONE;
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputDescription($component) : ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_NAME:
                return $this->__('Search users whose name contains this string', 'pop-users');
            case self::COMPONENT_FILTERINPUT_EMAILS:
                return $this->__('Search users with any of the provided emails', 'pop-users');
            default:
                return null;
        }
    }
}
