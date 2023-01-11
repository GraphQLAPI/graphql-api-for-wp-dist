<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTags\ComponentProcessors;

use PoPCMSSchema\Tags\ComponentProcessors\TagFilterInputContainerComponentProcessor;
class PostTagFilterInputContainerComponentProcessor extends TagFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
    /**
     * @return string[]
     */
    protected function getFilterInputHookNames() : array
    {
        $item0Unpacked = parent::getFilterInputHookNames();
        return \array_merge($item0Unpacked, [self::HOOK_FILTER_INPUTS]);
    }
}
