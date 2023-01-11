<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserRoles\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\UserRoles\FilterInputs\ExcludeUserRolesFilterInput;
use PoPCMSSchema\UserRoles\FilterInputs\UserRolesFilterInput;
class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public const COMPONENT_FILTERINPUT_USER_ROLES = 'filterinput-user-roles';
    public const COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES = 'filterinput-exclude-user-roles';
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\UserRoles\FilterInputs\UserRolesFilterInput|null
     */
    private $userRolesFilterInput;
    /**
     * @var \PoPCMSSchema\UserRoles\FilterInputs\ExcludeUserRolesFilterInput|null
     */
    private $excludeUserRolesFilterInput;
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
     * @param \PoPCMSSchema\UserRoles\FilterInputs\UserRolesFilterInput $userRolesFilterInput
     */
    public final function setUserRolesFilterInput($userRolesFilterInput) : void
    {
        $this->userRolesFilterInput = $userRolesFilterInput;
    }
    protected final function getUserRolesFilterInput() : UserRolesFilterInput
    {
        /** @var UserRolesFilterInput */
        return $this->userRolesFilterInput = $this->userRolesFilterInput ?? $this->instanceManager->getInstance(UserRolesFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\UserRoles\FilterInputs\ExcludeUserRolesFilterInput $excludeUserRolesFilterInput
     */
    public final function setExcludeUserRolesFilterInput($excludeUserRolesFilterInput) : void
    {
        $this->excludeUserRolesFilterInput = $excludeUserRolesFilterInput;
    }
    protected final function getExcludeUserRolesFilterInput() : ExcludeUserRolesFilterInput
    {
        /** @var ExcludeUserRolesFilterInput */
        return $this->excludeUserRolesFilterInput = $this->excludeUserRolesFilterInput ?? $this->instanceManager->getInstance(ExcludeUserRolesFilterInput::class);
    }
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUT_USER_ROLES, self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInput($component) : ?FilterInputInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_USER_ROLES:
                return $this->getUserRolesFilterInput();
            case self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES:
                return $this->getExcludeUserRolesFilterInput();
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
            case self::COMPONENT_FILTERINPUT_USER_ROLES:
                return 'roles';
            case self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES:
                return 'excludeRoles';
            default:
                return parent::getName($component);
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputTypeResolver($component) : InputTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_USER_ROLES:
                return $this->getStringScalarTypeResolver();
            case self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES:
                return $this->getStringScalarTypeResolver();
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
            case self::COMPONENT_FILTERINPUT_USER_ROLES:
            case self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES:
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
            case self::COMPONENT_FILTERINPUT_USER_ROLES:
                return $this->__('Get the users with given roles', 'user-roles');
            case self::COMPONENT_FILTERINPUT_EXCLUDE_USER_ROLES:
                return $this->__('Get the users without the given roles', 'user-roles');
            default:
                return null;
        }
    }
}
