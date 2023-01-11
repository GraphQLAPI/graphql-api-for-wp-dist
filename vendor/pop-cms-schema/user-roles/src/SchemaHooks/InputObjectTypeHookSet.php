<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserRoles\SchemaHooks;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\UserRoles\FilterInputs\ExcludeUserRolesFilterInput;
use PoPCMSSchema\UserRoles\FilterInputs\UserRolesFilterInput;
use PoPCMSSchema\UserRoles\Module;
use PoPCMSSchema\UserRoles\ModuleConfiguration;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\AbstractUsersFilterInputObjectTypeResolver;
class InputObjectTypeHookSet extends AbstractHookSet
{
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
    protected function init() : void
    {
        App::addFilter(HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS, \Closure::fromCallable([$this, 'getInputFieldNameTypeResolvers']), 10, 2);
        App::addFilter(HookNames::INPUT_FIELD_DESCRIPTION, \Closure::fromCallable([$this, 'getInputFieldDescription']), 10, 3);
        App::addFilter(HookNames::ADMIN_INPUT_FIELD_NAMES, \Closure::fromCallable([$this, 'getSensitiveInputFieldNames']), 10, 2);
        App::addFilter(HookNames::INPUT_FIELD_TYPE_MODIFIERS, \Closure::fromCallable([$this, 'getInputFieldTypeModifiers']), 10, 3);
        App::addFilter(HookNames::INPUT_FIELD_FILTER_INPUT, \Closure::fromCallable([$this, 'getInputFieldFilterInput']), 10, 3);
    }
    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    public function getInputFieldNameTypeResolvers($inputFieldNameTypeResolvers, $inputObjectTypeResolver) : array
    {
        if (!$inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver) {
            return $inputFieldNameTypeResolvers;
        }
        return \array_merge($inputFieldNameTypeResolvers, ['roles' => $this->getStringScalarTypeResolver(), 'excludeRoles' => $this->getStringScalarTypeResolver()]);
    }
    /**
     * @param string[] $adminInputFieldNames
     * @return string[]
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    public function getSensitiveInputFieldNames($adminInputFieldNames, $inputObjectTypeResolver) : array
    {
        if (!$inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver) {
            return $adminInputFieldNames;
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatUserRoleAsSensitiveData()) {
            $adminInputFieldNames[] = 'roles';
            $adminInputFieldNames[] = 'excludeRoles';
        }
        return $adminInputFieldNames;
    }
    /**
     * @param string|null $inputFieldDescription
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldDescription, $inputObjectTypeResolver, $inputFieldName) : ?string
    {
        if (!$inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver) {
            return $inputFieldDescription;
        }
        switch ($inputFieldName) {
            case 'roles':
                return $this->__('Filter users by role(s)', 'user-roles');
            case 'excludeRoles':
                return $this->__('Filter users by excluding role(s)', 'user-roles');
            default:
                return $inputFieldDescription;
        }
    }
    /**
     * @param int $inputFieldTypeModifiers
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldTypeModifiers($inputFieldTypeModifiers, $inputObjectTypeResolver, $inputFieldName) : int
    {
        if (!$inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver) {
            return $inputFieldTypeModifiers;
        }
        switch ($inputFieldName) {
            case 'roles':
            case 'excludeRoles':
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return $inputFieldTypeModifiers;
        }
    }
    /**
     * @param \PoP\ComponentModel\FilterInputs\FilterInputInterface|null $inputFieldFilterInput
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldFilterInput, $inputObjectTypeResolver, $inputFieldName) : ?FilterInputInterface
    {
        if (!$inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver) {
            return $inputFieldFilterInput;
        }
        switch ($inputFieldName) {
            case 'roles':
                return $this->getUserRolesFilterInput();
            case 'excludeRoles':
                return $this->getExcludeUserRolesFilterInput();
            default:
                return $inputFieldFilterInput;
        }
    }
}
