<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterServiceTagInterface;
use WP_Post;

abstract class AbstractViewSourceEndpointExecuter extends AbstractCPTEndpointExecuter implements EndpointExecuterServiceTagInterface
{
    protected function getView(): string
    {
        return RequestParams::VIEW_SOURCE;
    }

    public function executeEndpoint(): void
    {
        /** Add the excerpt, which is the description of the GraphQL query */
        \add_filter(
            'the_content',
            \Closure::fromCallable([$this, 'maybeGetGraphQLQuerySourceContent'])
        );
    }

    /**
     * Render the GraphQL Query CPT
     * @param string $content
     */
    public function maybeGetGraphQLQuerySourceContent($content): string
    {
        $customPost = \PoP\Root\App::getState(['routing', 'queried-object']);
        // Make sure there is a post (eg: it has not been deleted)
        if ($customPost !== null) {
            return $this->getGraphQLQuerySourceContent($content, $customPost);
        }
        return $content;
    }

    /**
     * Render the GraphQL Query CPT
     * @param string $content
     * @param \WP_Post $graphQLQueryPost
     */
    protected function getGraphQLQuerySourceContent($content, $graphQLQueryPost): string
    {
        // Commented out Prettify
        // // $scriptSrc = 'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js'
        // $mainPluginURL = App::getMainPlugin()->getPluginURL();
        // $scriptSrc = $mainPluginURL . 'assets/js/vendors/code-prettify/run_prettify.js';
        // /**
        //  * Prettyprint the code
        //  */
        // $content .= sprintf(
        //     '<script src="%s"></script>',
        //     $scriptSrc
        // );

        /**
         * Using highlight.js
         *
         * @see https://highlightjs.org/usage/
         */
        $linkTagPlaceholder = '<link rel="stylesheet" href="%s">';
        $scriptTagPlaceholder = '<script src="%s"></script>';
        $mainPluginURL = App::getMainPlugin()->getPluginURL();
        $content .= sprintf(
            $linkTagPlaceholder,
            $mainPluginURL . 'assets/css/vendors/highlight-11.6.0/a11y-dark.min.css'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/highlight.min.js'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/graphql.min.js'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/json.min.js'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/run_highlight.js'
        );

        return $content;
    }
}
