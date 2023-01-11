<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers;

use PoP\Root\App;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\Root\Services\BasicServiceTrait;
abstract class AbstractTypeResolver implements \PoP\ComponentModel\TypeResolvers\TypeResolverInterface
{
    use BasicServiceTrait;
    /**
     * @var array<string,mixed[]>|null
     */
    protected $schemaDefinition;
    /**
     * @var \PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface|null
     */
    private $schemaNamespacingService;
    /**
     * @var \PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface|null
     */
    private $schemaDefinitionService;
    /**
     * @var \PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface|null
     */
    private $attachableExtensionManager;
    /**
     * @param \PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface $schemaNamespacingService
     */
    public final function setSchemaNamespacingService($schemaNamespacingService) : void
    {
        $this->schemaNamespacingService = $schemaNamespacingService;
    }
    protected final function getSchemaNamespacingService() : SchemaNamespacingServiceInterface
    {
        /** @var SchemaNamespacingServiceInterface */
        return $this->schemaNamespacingService = $this->schemaNamespacingService ?? $this->instanceManager->getInstance(SchemaNamespacingServiceInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface $schemaDefinitionService
     */
    public final function setSchemaDefinitionService($schemaDefinitionService) : void
    {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }
    protected final function getSchemaDefinitionService() : SchemaDefinitionServiceInterface
    {
        /** @var SchemaDefinitionServiceInterface */
        return $this->schemaDefinitionService = $this->schemaDefinitionService ?? $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface $attachableExtensionManager
     */
    public final function setAttachableExtensionManager($attachableExtensionManager) : void
    {
        $this->attachableExtensionManager = $attachableExtensionManager;
    }
    protected final function getAttachableExtensionManager() : AttachableExtensionManagerInterface
    {
        /** @var AttachableExtensionManagerInterface */
        return $this->attachableExtensionManager = $this->attachableExtensionManager ?? $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
    }
    public function getNamespace() : string
    {
        return $this->getSchemaNamespacingService()->getSchemaNamespace($this->getClassToNamespace());
    }
    protected function getClassToNamespace() : string
    {
        /** @var string */
        return \get_called_class();
    }
    public final function getNamespacedTypeName() : string
    {
        return $this->getSchemaNamespacingService()->getSchemaNamespacedName($this->getNamespace(), $this->getTypeName());
    }
    public final function getMaybeNamespacedTypeName() : string
    {
        return App::getState('namespace-types-and-interfaces') ? $this->getNamespacedTypeName() : $this->getTypeName();
    }
    public final function getTypeOutputKey() : string
    {
        // Do not make the first letter lowercase, or namespaced names look bad
        return $this->getMaybeNamespacedTypeName();
    }
    public function getTypeDescription() : ?string
    {
        return null;
    }
    public function isIntrospectionType() : bool
    {
        return \false;
    }
}
