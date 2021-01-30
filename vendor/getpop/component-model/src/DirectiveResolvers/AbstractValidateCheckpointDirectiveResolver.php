<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
abstract class AbstractValidateCheckpointDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractValidateConditionDirectiveResolver
{
    /**
     * Validate checkpoints
     *
     * @param TypeResolverInterface $typeResolver
     * @return boolean
     */
    protected function validateCondition(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool
    {
        $checkpointSet = $this->getValidationCheckpointSet($typeResolver);
        $engine = \PoP\ComponentModel\Facades\Engine\EngineFacade::getInstance();
        $validation = $engine->validateCheckpoints($checkpointSet);
        return !\PoP\ComponentModel\Misc\GeneralUtils::isError($validation);
    }
    /**
     * Provide the checkpoint to validate
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    protected abstract function getValidationCheckpointSet(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array;
}
