<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\Hooks;

use PoP\AccessControl\ComponentConfiguration;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoPSchema\UserState\FieldResolvers\AbstractUserStateFieldResolver;
use PoP\AccessControl\Hooks\AbstractAccessControlForFieldsHookSet;
class DisableUserStateFieldsIfUserIsNotLoggedInAccessControlForFieldsInPrivateSchemaHookSet extends \PoP\AccessControl\Hooks\AbstractAccessControlForFieldsHookSet
{
    /**
     * If the user is not logged in, and we are in private mode,
     * then remove the field
     */
    protected function enabled() : bool
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        $isUserLoggedIn = $vars['global-userstate']['is-user-logged-in'];
        return \PoP\AccessControl\ComponentConfiguration::usePrivateSchemaMode() && !$isUserLoggedIn;
    }
    /**
     * Apply to all fields
     *
     * @return array
     */
    protected function getFieldNames() : array
    {
        return [];
    }
    /**
     * Remove the fieldNames if the fieldResolver is an instance of the "user state" one
     *
     * @param boolean $include
     * @param TypeResolverInterface $typeResolver
     * @param FieldResolverInterface $fieldResolver
     * @param string $fieldName
     * @return boolean
     */
    protected function removeFieldName(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, \PoP\ComponentModel\FieldResolvers\FieldResolverInterface $fieldResolver, array $fieldInterfaceResolverClasses, string $fieldName) : bool
    {
        return $fieldResolver instanceof \PoPSchema\UserState\FieldResolvers\AbstractUserStateFieldResolver;
    }
}
