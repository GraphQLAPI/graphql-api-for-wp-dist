<?php

declare (strict_types=1);
namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoPAPI\API\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
abstract class AbstractTypeSchemaDefinitionProvider extends \PoPAPI\API\ObjectModels\SchemaDefinition\AbstractSchemaDefinitionProvider implements \PoPAPI\API\ObjectModels\SchemaDefinition\TypeSchemaDefinitionProviderInterface
{
    /**
     * @var array<string,RelationalTypeResolverInterface> Key: directive resolver class, Value: The Type Resolver Class which loads the directive
     */
    protected $accessedFieldDirectiveResolverClassRelationalTypeResolvers = [];
    /**
     * @var \PoP\ComponentModel\TypeResolvers\TypeResolverInterface
     */
    protected $typeResolver;
    public function __construct(TypeResolverInterface $typeResolver)
    {
        $this->typeResolver = $typeResolver;
    }
    /**
     * @return array<string,RelationalTypeResolverInterface> Key: directive resolver class, Value: The Type Resolver Class which loads the directive
     */
    public final function getAccessedFieldDirectiveResolverClassRelationalTypeResolvers() : array
    {
        return $this->accessedFieldDirectiveResolverClassRelationalTypeResolvers;
    }
    /**
     * @return array<string,mixed>
     */
    public function getSchemaDefinition() : array
    {
        $schemaDefinition = [SchemaDefinition::NAME => $this->typeResolver->getMaybeNamespacedTypeName()];
        if ($description = $this->typeResolver->getTypeDescription()) {
            $schemaDefinition[SchemaDefinition::DESCRIPTION] = $description;
        }
        $schemaDefinition[SchemaDefinition::EXTENSIONS] = $this->getNamedTypeExtensions();
        return $schemaDefinition;
    }
    /**
     * @return array<string,mixed>
     */
    protected function getNamedTypeExtensions() : array
    {
        return [SchemaDefinition::NAMESPACED_NAME => $this->typeResolver->getNamespacedTypeName(), SchemaDefinition::ELEMENT_NAME => $this->typeResolver->getTypeName()];
    }
}
