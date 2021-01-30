<?php

declare (strict_types=1);
namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\AccessControl\DirectiveResolvers\DisableAccessForDirectivesDirectiveResolver;
use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator;
abstract class AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator extends \PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator
{
    protected function getMandatoryDirectives($entryValue = null) : array
    {
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        $disableAccessDirective = $fieldQueryInterpreter->getDirective(\PoP\AccessControl\DirectiveResolvers\DisableAccessForDirectivesDirectiveResolver::getDirectiveName());
        return [$disableAccessDirective];
    }
}
