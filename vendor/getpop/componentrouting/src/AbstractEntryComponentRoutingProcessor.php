<?php

declare (strict_types=1);
namespace PoP\ComponentRouting;

abstract class AbstractEntryComponentRoutingProcessor extends \PoP\ComponentRouting\AbstractComponentRoutingProcessor
{
    /**
     * @return string[]
     */
    public function getGroups() : array
    {
        return [\PoP\ComponentRouting\ComponentRoutingGroups::ENTRYCOMPONENT];
    }
}
