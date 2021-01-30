<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\DirectiveResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\UserState\CheckpointSets\UserStateCheckpointSets;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateCheckpointDirectiveResolver;
class ValidateIsUserLoggedInDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractValidateCheckpointDirectiveResolver
{
    const DIRECTIVE_NAME = 'validateIsUserLoggedIn';
    public static function getDirectiveName() : string
    {
        return self::DIRECTIVE_NAME;
    }
    protected function getValidationCheckpointSet(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        return \PoPSchema\UserState\CheckpointSets\UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER;
    }
    protected function getValidationFailedMessage(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array $failedDataFields) : string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $errorMessage = $this->isValidatingDirective() ? $translationAPI->__('You must be logged in to access directives in field(s) \'%s\' for type \'%s\'', 'user-state') : $translationAPI->__('You must be logged in to access field(s) \'%s\' for type \'%s\'', 'user-state');
        return \sprintf($errorMessage, \implode($translationAPI->__('\', \''), $failedDataFields), $typeResolver->getMaybeNamespacedTypeName());
    }
    public function getSchemaDirectiveDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('It validates if the user is logged-in', 'component-model');
    }
}
