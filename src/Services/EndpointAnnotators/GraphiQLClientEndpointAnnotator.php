<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\EndpointGraphiQLBlock;
use WP_Post;

class GraphiQLClientEndpointAnnotator extends AbstractClientEndpointAnnotator implements CustomEndpointAnnotatorServiceTagInterface
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Blocks\EndpointGraphiQLBlock|null
     */
    private $endpointGraphiQLBlock;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Blocks\EndpointGraphiQLBlock $endpointGraphiQLBlock
     */
    final public function setEndpointGraphiQLBlock($endpointGraphiQLBlock): void
    {
        $this->endpointGraphiQLBlock = $endpointGraphiQLBlock;
    }
    final protected function getEndpointGraphiQLBlock(): EndpointGraphiQLBlock
    {
        /** @var EndpointGraphiQLBlock */
        return $this->endpointGraphiQLBlock = $this->endpointGraphiQLBlock ?? $this->instanceManager->getInstance(EndpointGraphiQLBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return ClientFunctionalityModuleResolver::GRAPHIQL_FOR_CUSTOM_ENDPOINTS;
    }

    /**
     * Add actions to the CPT list
     * @param array<string,string> $actions
     * @param \WP_Post $post
     */
    public function addCustomPostTypeTableActions(&$actions, $post): void
    {
        // Check the client has not been disabled in the CPT
        if (!$this->isClientEnabled($post)) {
            return;
        }

        if ($permalink = \get_permalink($post->ID)) {
            $title = \_draft_or_post_title();
            $actions['graphiql'] = sprintf(
                '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                \add_query_arg(
                    RequestParams::VIEW,
                    RequestParams::VIEW_GRAPHIQL,
                    $permalink
                ),
                /* translators: %s: Post title. */
                \esc_attr(\sprintf(\__('GraphiQL &#8220;%s&#8221;'), $title)),
                __('GraphiQL', 'graphql-api')
            );
        }
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getEndpointGraphiQLBlock();
    }
}
