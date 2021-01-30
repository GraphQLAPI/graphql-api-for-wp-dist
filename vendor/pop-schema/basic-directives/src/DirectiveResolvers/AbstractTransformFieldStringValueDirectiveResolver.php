<?php

declare (strict_types=1);
namespace PoPSchema\BasicDirectives\DirectiveResolvers;

use PoP\ComponentModel\Feedback\Tokens;
use PoP\Translation\Facades\TranslationAPIFacade;
/**
 * Apply a transformation to the string
 */
abstract class AbstractTransformFieldStringValueDirectiveResolver extends \PoPSchema\BasicDirectives\DirectiveResolvers\AbstractTransformFieldValueDirectiveResolver
{
    protected function validateTypeIsString($value, $id, string $field, string $fieldOutputKey, array &$dbErrors, array &$dbWarnings)
    {
        if (!\is_string($value)) {
            $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
            $dbWarnings[(string) $id][] = [\PoP\ComponentModel\Feedback\Tokens::PATH => [$this->directive], \PoP\ComponentModel\Feedback\Tokens::MESSAGE => \sprintf($translationAPI->__('Directive \'%s\' from field \'%s\' cannot be applied on object with ID \'%s\' because it is not a string', 'practical-directives'), $this->getDirectiveName(), $fieldOutputKey, $id)];
            return \false;
        }
        return \true;
    }
}
