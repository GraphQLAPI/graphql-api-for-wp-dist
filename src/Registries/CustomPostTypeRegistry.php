<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface;

class CustomPostTypeRegistry implements CustomPostTypeRegistryInterface
{
    /**
     * @var array<string,CustomPostTypeInterface> serviceDefinitionID => CPT
     */
    protected $customPostTypes = [];

    /**
     * Keep the service definition, to unregister the CPTs
     * @param \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface $customPostType
     * @param string $serviceDefinitionID
     */
    public function addCustomPostType(
        $customPostType,
        $serviceDefinitionID
    ): void {
        $this->customPostTypes[$serviceDefinitionID] = $customPostType;
    }
    /**
     * @return array<string,CustomPostTypeInterface> serviceDefinitionID => CPT
     */
    public function getCustomPostTypes(): array
    {
        return $this->customPostTypes;
    }
}
