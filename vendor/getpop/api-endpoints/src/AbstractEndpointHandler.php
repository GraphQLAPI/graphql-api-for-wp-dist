<?php

declare (strict_types=1);
namespace PoP\APIEndpoints;

use PoP\APIEndpoints\EndpointUtils;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
abstract class AbstractEndpointHandler extends \PoP\Root\Services\AbstractAutomaticallyInstantiatedService
{
    /**
     * @var string|null
     */
    protected $endpoint = null;
    /**
     * Provide the endpoint
     */
    protected abstract function getEndpoint() : string;
    /**
     * Initialize the client
     */
    public function initialize() : void
    {
        /**
         * Subject to the endpoint having been defined
         */
        if ($this->endpoint = $this->getEndpoint()) {
            // Make sure the endpoint has trailing "/" on both ends
            $this->endpoint = \PoP\APIEndpoints\EndpointUtils::slashURI($this->endpoint);
        }
    }
    /**
     * If `true`, the endpoint must exactly match the URL
     * If `false`, the endpoint is triggered when it is contained at the end of the URL
     *
     * @return boolean
     */
    protected function doesEndpointMatchWholeURL() : bool
    {
        return \true;
    }
    /**
     * Get the requested URI to compare it against the endpoint
     *
     * @return void
     */
    protected function getRequestedURI() : string
    {
        // Check if the URL ends with either /api/graphql/ or /api/rest/ or /api/
        $uri = \PoP\APIEndpoints\EndpointUtils::removeMarkersFromURI($_SERVER['REQUEST_URI']);
        // Same as the endpoint, make sure the URI has "/" in both ends
        return \PoP\APIEndpoints\EndpointUtils::slashURI($uri);
    }
    /**
     * Indicate if the endpoint has been requested
     *
     * @return void
     */
    protected function isEndpointRequested() : bool
    {
        /**
         * For static analysis
         */
        if ($this->endpoint === null) {
            return \false;
        }
        // Compare the formatted requested URI against the endpoint
        $uri = $this->getRequestedURI();
        if ($this->doesEndpointMatchWholeURL()) {
            return $uri == $this->endpoint;
        }
        return \PoP\APIEndpoints\EndpointUtils::doesURIEndWith($uri, $this->endpoint);
    }
}
