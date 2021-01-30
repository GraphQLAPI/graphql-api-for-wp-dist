<?php

namespace PrefixedByPoP\FastRoute\DataGenerator;

class GroupPosBased extends \PrefixedByPoP\FastRoute\DataGenerator\RegexBasedAbstract
{
    protected function getApproxChunkSize()
    {
        return 10;
    }
    protected function processChunk($regexToRoutesMap)
    {
        $routeMap = [];
        $regexes = [];
        $offset = 1;
        foreach ($regexToRoutesMap as $regex => $route) {
            $regexes[] = $regex;
            $routeMap[$offset] = [$route->handler, $route->variables];
            $offset += \count($route->variables);
        }
        $regex = '~^(?:' . \implode('|', $regexes) . ')$~';
        return ['regex' => $regex, 'routeMap' => $routeMap];
    }
}
