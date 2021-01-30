<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver;
abstract class AbstractValidateIsUserLoggedInForDirectivesPublicSchemaTypeResolverDecorator extends \PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator
{
    /**
     * Verify that the user is logged in before checking the roles/capabilities
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getPrecedingMandatoryDirectivesForDirectives(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        $mandatoryDirectivesForDirectives = [];
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        // This is the required "validateIsUserLoggedIn" directive
        $validateIsUserLoggedInDirective = $fieldQueryInterpreter->getDirective(\PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver::getDirectiveName());
        // Add the mapping
        foreach ($this->getDirectiveResolverClasses() as $needValidateIsUserLoggedInDirective) {
            $mandatoryDirectivesForDirectives[$needValidateIsUserLoggedInDirective::getDirectiveName()] = [$validateIsUserLoggedInDirective];
        }
        return $mandatoryDirectivesForDirectives;
    }
    /**
     * Provide the classes for all the directiveResolverClasses that need the "validateIsUserLoggedIn" directive
     *
     * @return array
     */
    protected abstract function getDirectiveResolverClasses() : array;
}
