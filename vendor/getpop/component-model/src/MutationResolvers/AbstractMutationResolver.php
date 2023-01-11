<?php

declare (strict_types=1);
namespace PoP\ComponentModel\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Services\BasicServiceTrait;
abstract class AbstractMutationResolver implements \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
{
    use BasicServiceTrait;
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
    }
    public function getErrorType() : int
    {
        return \PoP\ComponentModel\MutationResolvers\ErrorTypes::DESCRIPTIONS;
    }
}
