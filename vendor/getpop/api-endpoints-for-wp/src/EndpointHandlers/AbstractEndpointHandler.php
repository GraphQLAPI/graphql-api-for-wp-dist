<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP\EndpointHandlers;

abstract class AbstractEndpointHandler extends \PoP\APIEndpoints\AbstractEndpointHandler
{
    /**
     * Initialize the client
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        /**
         * Subject to the endpoint having been defined
         */
        if ($this->endpoint) {
            /**
             * Register the endpoints
             */
            \add_action('init', [$this, 'addRewriteEndpoints']);
            \add_filter('query_vars', [$this, 'addQueryVar'], 10, 1);
            \add_action('parse_request', [$this, 'parseRequest']);

            // // If it is a partial endpoint, we must add all the combinations of routes to Cortex
            // if (!$this->doesEndpointMatchWholeURL()) {
            //     \add_filter(
            //         'route-endpoints',
            //         [$this, 'getRouteEndpoints'],
            //         10,
            //         1
            //     );
            // }
        }
    }

    // public function getRouteEndpoints(array $endpoints): array
    // {
    //     return array_merge(
    //         $endpoints,
    //         [
    //             $this->endpoint
    //         ]
    //     );
    // }

    /**
     * If the endpoint for the client is requested, do something
     *
     * @return void
     */
    public function parseRequest(): void
    {
        if ($this->isEndpointRequested()) {
            $this->executeEndpoint();
        }
    }

    /**
     * Execute the endpoint. Function to override
     *
     * @return void
     */
    protected function executeEndpoint(): void
    {
        // Do nothing here, override
    }

    /**
     * The mask indicates where to apply the endpoint rewriting
     * @see https://codex.wordpress.org/Rewrite_API/add_rewrite_endpoint
     *
     * Using EP_ROOT means that whole URL must match the endpoint.
     *
     * @return boolean
     */
    protected function getRewriteMask(): int
    {
        return $this->doesEndpointMatchWholeURL() ? constant('EP_ROOT') : constant('EP_ALL');
    }

    /**
     * Add the endpoints to WordPress
     *
     * @return void
     */
    public function addRewriteEndpoints()
    {
        // The endpoint passed to `add_rewrite_endpoint` cannot have "/" on either end, or it doesn't work
        \add_rewrite_endpoint(trim($this->endpoint, '/'), $this->getRewriteMask());
    }

    /**
     * Add the endpoint query vars
     *
     * @param array $query_vars
     * @return void
     */
    public function addQueryVar($query_vars)
    {
        $query_vars[] = $this->endpoint;
        return $query_vars;
    }
}
