<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\Hooks;

use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\AccessControl\Hooks\AbstractAccessControlForFieldsInPrivateSchemaHookSet;
abstract class AbstractDisableFieldsIfUserIsNotLoggedInAccessControlForFieldsInPrivateSchemaHookSet extends \PoP\AccessControl\Hooks\AbstractAccessControlForFieldsInPrivateSchemaHookSet
{
    /**
     * Decide if to remove the fieldNames
     *
     * @param TypeResolverInterface $typeResolver
     * @param FieldResolverInterface $fieldResolver
     * @param string $fieldName
     * @return boolean
     */
    protected function removeFieldName(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, \PoP\ComponentModel\FieldResolvers\FieldResolverInterface $fieldResolver, array $fieldInterfaceResolverClasses, string $fieldName) : bool
    {
        /**
         * If the user is not logged in, then remove the field
         */
        return !$this->isUserLoggedIn();
    }
    /**
     * Helper function to get user state from $vars
     *
     * @return boolean
     */
    protected function isUserLoggedIn() : bool
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        return $vars['global-userstate']['is-user-logged-in'];
    }
}
