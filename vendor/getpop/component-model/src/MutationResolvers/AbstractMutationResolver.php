<?php

declare (strict_types=1);
namespace PoP\ComponentModel\MutationResolvers;

abstract class AbstractMutationResolver implements \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
{
    public function validateErrors(array $form_data) : ?array
    {
        return null;
    }
    public function validateWarnings(array $form_data) : ?array
    {
        return null;
    }
    public function getErrorType() : int
    {
        return \PoP\ComponentModel\MutationResolvers\ErrorTypes::DESCRIPTIONS;
    }
}
