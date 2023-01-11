<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface;

interface CustomPostTypeRegistryInterface
{
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface $customPostType
     * @param string $serviceDefinitionID
     */
    public function addCustomPostType($customPostType, $serviceDefinitionID): void;
    /**
     * @return array<string,CustomPostTypeInterface> serviceDefinitionID => CPT
     */
    public function getCustomPostTypes(): array;
}
