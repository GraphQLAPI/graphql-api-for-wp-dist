<?php

declare (strict_types=1);
namespace PoP\Routing;

class Routes
{
    use RoutesTrait;
    /**
     * @var string
     */
    public static $MAIN = '';
    /**
     * @return array<string, string>
     */
    protected static function getRouteNameAndVariableRefs() : array
    {
        return ['main' => &self::$MAIN];
    }
}
