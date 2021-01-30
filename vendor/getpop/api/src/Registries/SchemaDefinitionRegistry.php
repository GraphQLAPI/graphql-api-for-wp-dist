<?php

declare (strict_types=1);
namespace PoP\API\Registries;

use PoP\API\Cache\CacheTypes;
use PoP\API\Cache\CacheUtils;
use PoP\API\ComponentConfiguration;
use PoP\Engine\ObjectFacades\RootObjectFacade;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\API\Registries\SchemaDefinitionRegistryInterface;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
class SchemaDefinitionRegistry implements \PoP\API\Registries\SchemaDefinitionRegistryInterface
{
    /**
     * @var array<string, array>
     */
    protected $schemaInstances = [];
    /**
     * Create a key from the arrays, to cache the results
     *
     * @param array<string, mixed> $fieldArgs
     * @param array|null $options
     * @return string
     */
    protected function getArgumentKey(?array $fieldArgs, ?array $options) : string
    {
        return \json_encode($fieldArgs ?? []) . \json_encode($options ?? []);
    }
    /**
     * Produce the schema definition. It can store the value in the cache.
     * Use cache with care: if the schema is dynamic, it should not be cached.
     * Public schema: can cache, Private schema: cannot cache.
     *
     * @param array|null $fieldArgs
     * @param array|null $options
     * @return array
     */
    public function &getSchemaDefinition(?array $fieldArgs = [], ?array $options = []) : array
    {
        // Create a key from the arrays, to cache the results
        $key = $this->getArgumentKey($fieldArgs, $options);
        if (!isset($this->schemaInstances[$key])) {
            // Attempt to retrieve from the cache, if enabled
            if ($useCache = \PoP\API\ComponentConfiguration::useSchemaDefinitionCache()) {
                $persistentCache = \PoP\ComponentModel\Facades\Cache\PersistentCacheFacade::getInstance();
                // Use different caches for the normal and namespaced schemas,  or
                // it throws exception if switching without deleting the cache (eg: when passing ?use_namespace=1)
                $cacheType = \PoP\API\Cache\CacheTypes::SCHEMA_DEFINITION;
                $cacheKeyComponents = \PoP\API\Cache\CacheUtils::getSchemaCacheKeyComponents();
                // For the persistentCache, use a hash to remove invalid characters (such as "()")
                $cacheKey = \hash('md5', $key . '|' . \json_encode($cacheKeyComponents));
            }
            $schemaDefinition = null;
            if ($useCache) {
                if ($persistentCache->hasCache($cacheKey, $cacheType)) {
                    $schemaDefinition = $persistentCache->getCache($cacheKey, $cacheType);
                }
            }
            // If either not using cache, or using but the value had not been cached, then calculate the value
            if ($schemaDefinition === null) {
                $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
                /**
                 * @var RootTypeResolver
                 */
                $rootTypeResolver = $instanceManager->getInstance(\PoP\Engine\TypeResolvers\RootTypeResolver::class);
                $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
                $root = \PoP\Engine\ObjectFacades\RootObjectFacade::getInstance();
                $schemaDefinition = $rootTypeResolver->resolveValue($root, $fieldQueryInterpreter->getField('fullSchema', $fieldArgs ?? []), null, null, $options);
                // Store in the cache
                if ($useCache) {
                    $persistentCache->storeCache($cacheKey, $cacheType, $schemaDefinition);
                }
            }
            // Assign to in-memory cache
            $this->schemaInstances[$key] = $schemaDefinition;
        }
        return $this->schemaInstances[$key];
    }
}
