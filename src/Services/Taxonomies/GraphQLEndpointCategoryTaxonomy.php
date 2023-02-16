<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Taxonomies;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

class GraphQLEndpointCategoryTaxonomy extends AbstractCategory
{
    use StandaloneServiceTrait;

    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType|null
     */
    private $graphQLPersistedQueryEndpointCustomPostType;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType|null
     */
    private $graphQLCustomEndpointCustomPostType;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType
     */
    final public function setGraphQLPersistedQueryEndpointCustomPostType($graphQLPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPersistedQueryEndpointCustomPostType(): GraphQLPersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        return $this->graphQLPersistedQueryEndpointCustomPostType = $this->graphQLPersistedQueryEndpointCustomPostType ?? InstanceManagerFacade::getInstance()->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType
     */
    final public function setGraphQLCustomEndpointCustomPostType($graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        /** @var GraphQLCustomEndpointCustomPostType */
        return $this->graphQLCustomEndpointCustomPostType = $this->graphQLCustomEndpointCustomPostType ?? InstanceManagerFacade::getInstance()->getInstance(GraphQLCustomEndpointCustomPostType::class);
    }

    public function getTaxonomy(): string
    {
        return 'graphql-endpoint-category';
    }

    /**
     * @param bool $titleCase
     */
    public function getTaxonomyName($titleCase = true): string
    {
        return $titleCase ? \__('GraphQL Endpoint Category', 'graphql-api') : \__('GraphQL endpoint category', 'graphql-api');
    }

    /**
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    public function getTaxonomyPluralNames($titleCase = true): string
    {
        return $titleCase ? \__('Endpoint Categories', 'graphql-api') : \__('endpoint categories', 'graphql-api');
    }

    /**
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->getGraphQLPersistedQueryEndpointCustomPostType()->getCustomPostType(),
            $this->getGraphQLCustomEndpointCustomPostType()->getCustomPostType(),
        ];
    }
}
