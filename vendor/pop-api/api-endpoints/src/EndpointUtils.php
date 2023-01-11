<?php

declare (strict_types=1);
namespace PoPAPI\APIEndpoints;

class EndpointUtils
{
    /**
     * Indicate if the URI ends with the given endpoint
     * @param string $uri
     */
    public static function removeMarkersFromURI($uri) : string
    {
        // Remove everything after "?" and "#"
        $symbols = ['?', '#'];
        foreach ($symbols as $symbol) {
            $symbolPos = \strpos($uri, $symbol);
            if ($symbolPos !== \false) {
                $uri = \substr($uri, 0, $symbolPos);
            }
        }
        return $uri;
    }
    /**
     * Make sure the URI has "/" at both ends
     * @param string $uri
     */
    public static function slashURI($uri) : string
    {
        return '/' . \trim($uri, '/') . '/';
    }
    /**
     * Indicate if the URI ends with the given endpoint
     * @param string $uri
     * @param string $endpointURI
     */
    public static function doesURIEndWith($uri, $endpointURI) : bool
    {
        return \substr($uri, -1 * \strlen($endpointURI)) == $endpointURI;
    }
}
