<?php

declare (strict_types=1);
namespace PoPAPI\API\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoPAPI\API\Module;
use PoPAPI\API\ModuleConfiguration;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoPAPI\API\PersistedQueries\PersistedFragmentManagerInterface;
use PoPAPI\API\PersistedQueries\PersistedQueryManagerInterface;
use PoPAPI\API\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoP\ComponentModel\Cache\PersistentCacheInterface|null
     */
    private $persistentCache;
    /**
     * @var \PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver|null
     */
    private $jsonObjectScalarTypeResolver;
    /**
     * @var \PoPAPI\API\PersistedQueries\PersistedFragmentManagerInterface|null
     */
    private $persistedFragmentManager;
    /**
     * @var \PoPAPI\API\PersistedQueries\PersistedQueryManagerInterface|null
     */
    private $persistedQueryManager;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
    /**
     * Cannot autowire with "#[Required]" because its calling `getNamespace`
     * on services.yaml produces an exception of PHP properties not initialized
     * in its depended services.
     * @param \PoP\ComponentModel\Cache\PersistentCacheInterface $persistentCache
     */
    public final function setPersistentCache($persistentCache) : void
    {
        $this->persistentCache = $persistentCache;
    }
    public final function getPersistentCache() : PersistentCacheInterface
    {
        /** @var PersistentCacheInterface */
        return $this->persistentCache = $this->persistentCache ?? $this->instanceManager->getInstance(PersistentCacheInterface::class);
    }
    /**
     * @param \PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver
     */
    public final function setJSONObjectScalarTypeResolver($jsonObjectScalarTypeResolver) : void
    {
        $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
    }
    protected final function getJSONObjectScalarTypeResolver() : JSONObjectScalarTypeResolver
    {
        /** @var JSONObjectScalarTypeResolver */
        return $this->jsonObjectScalarTypeResolver = $this->jsonObjectScalarTypeResolver ?? $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
    }
    /**
     * @param \PoPAPI\API\PersistedQueries\PersistedFragmentManagerInterface $persistedFragmentManager
     */
    public final function setPersistedFragmentManager($persistedFragmentManager) : void
    {
        $this->persistedFragmentManager = $persistedFragmentManager;
    }
    protected final function getPersistedFragmentManager() : PersistedFragmentManagerInterface
    {
        /** @var PersistedFragmentManagerInterface */
        return $this->persistedFragmentManager = $this->persistedFragmentManager ?? $this->instanceManager->getInstance(PersistedFragmentManagerInterface::class);
    }
    /**
     * @param \PoPAPI\API\PersistedQueries\PersistedQueryManagerInterface $persistedQueryManager
     */
    public final function setPersistedQueryManager($persistedQueryManager) : void
    {
        $this->persistedQueryManager = $persistedQueryManager;
    }
    protected final function getPersistedQueryManager() : PersistedQueryManagerInterface
    {
        /** @var PersistedQueryManagerInterface */
        return $this->persistedQueryManager = $this->persistedQueryManager ?? $this->instanceManager->getInstance(PersistedQueryManagerInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver $booleanScalarTypeResolver
     */
    public final function setBooleanScalarTypeResolver($booleanScalarTypeResolver) : void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    protected final function getBooleanScalarTypeResolver() : BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver = $this->booleanScalarTypeResolver ?? $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [RootObjectTypeResolver::class];
    }
    public function isServiceEnabled() : bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->addFullSchemaFieldToSchema();
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['fullSchema'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'fullSchema':
                return $this->getJSONObjectScalarTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case 'fullSchema':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'fullSchema':
                return $this->__('The whole API schema, exposing what fields can be queried', 'api');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return mixed
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'fullSchema':
                // Convert from array to stdClass
                /** @var SchemaDefinitionServiceInterface */
                $schemaDefinitionService = $this->getSchemaDefinitionService();
                return (object) $schemaDefinitionService->getFullSchemaDefinition();
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
