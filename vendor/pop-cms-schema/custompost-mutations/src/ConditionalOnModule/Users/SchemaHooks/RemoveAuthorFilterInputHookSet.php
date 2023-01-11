<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMutations\ConditionalOnModule\Users\SchemaHooks;

use PoPCMSSchema\CustomPostMutations\ComponentProcessors\CustomPostMutationFilterInputContainerComponentProcessor;
class RemoveAuthorFilterInputHookSet extends \PoPCMSSchema\CustomPostMutations\ConditionalOnModule\Users\SchemaHooks\AbstractRemoveAuthorFilterInputHookSet
{
    protected function getHookNameToRemoveFilterInput() : string
    {
        return CustomPostMutationFilterInputContainerComponentProcessor::HOOK_FILTER_INPUTS;
    }
}
