<?php

declare (strict_types=1);
namespace PoPSchema\UserState\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\UserState\CheckpointSets\UserStateCheckpointSets;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\ErrorHandling\Error;
trait UserStateFieldResolverTrait
{
    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<array>|null A checkpoint set, or null
     * @param object $resultItem
     */
    protected function getValidationCheckpoints(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []) : ?array
    {
        return UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER;
    }
    /**
     * @param array<string, mixed> $fieldArgs
     * @param object $resultItem
     */
    protected function getValidationCheckpointsErrorMessage(Error $error, string $errorMessage, TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []) : string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return \sprintf($translationAPI->__('You must be logged in to access field \'%s\' for type \'%s\'', ''), $fieldName, $typeResolver->getMaybeNamespacedTypeName());
    }
}
