<?php

declare (strict_types=1);
namespace PoPAPI\API\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoPAPI\API\Module;
use PoPAPI\API\ModuleConfiguration;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoPAPI\API\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver|null
     */
    private $jsonObjectScalarTypeResolver;
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
