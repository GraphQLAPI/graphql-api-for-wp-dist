<?php

declare (strict_types=1);
namespace PoPSchema\UserRolesAccessControl\DirectiveResolvers;

use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver;
class ValidateDoesLoggedInUserHaveAnyRoleDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver
{
    const DIRECTIVE_NAME = 'validateDoesLoggedInUserHaveAnyRole';
    public static function getDirectiveName() : string
    {
        return self::DIRECTIVE_NAME;
    }
    protected function validateCondition(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        // If the user is not logged-in, then do nothing: directive `@validateIsUserLoggedIn` will already fail
        if (!$vars['global-userstate']['is-user-logged-in']) {
            return \true;
        }
        $roles = $this->directiveArgsForSchema['roles'];
        $userRoleTypeDataResolver = \PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade::getInstance();
        $userID = $vars['global-userstate']['current-user-id'];
        $userRoles = $userRoleTypeDataResolver->getUserRoles($userID);
        return !empty(\array_intersect($roles, $userRoles));
    }
    protected function getValidationFailedMessage(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array $failedDataFields) : string
    {
        $roles = $this->directiveArgsForSchema['roles'];
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $isValidatingDirective = $this->isValidatingDirective();
        if (\count($roles) == 1) {
            $errorMessage = $isValidatingDirective ? $translationAPI->__('You must have role \'%s\' to access directives in field(s) \'%s\' for type \'%s\'', 'user-roles') : $translationAPI->__('You must have role \'%s\' to access field(s) \'%s\' for type \'%s\'', 'user-roles');
        } else {
            $errorMessage = $isValidatingDirective ? $translationAPI->__('You must have any role from among \'%s\' to access directives in field(s) \'%s\' for type \'%s\'', 'user-roles') : $translationAPI->__('You must have any role from among \'%s\' to access field(s) \'%s\' for type \'%s\'', 'user-roles');
        }
        return \sprintf($errorMessage, \implode($translationAPI->__('\', \''), $roles), \implode($translationAPI->__('\', \''), $failedDataFields), $typeResolver->getMaybeNamespacedTypeName());
    }
    public function getSchemaDirectiveDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('It validates if the user has any of the roles provided through directive argument \'roles\'', 'component-model');
    }
    public function getSchemaDirectiveArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'roles', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Roles to validate if the logged-in user has (any of them)', 'component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]];
    }
}
