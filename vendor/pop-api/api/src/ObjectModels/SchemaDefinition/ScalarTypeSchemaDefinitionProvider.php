<?php

declare (strict_types=1);
namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoPAPI\API\Schema\SchemaDefinition;
use PoPAPI\API\Schema\TypeKinds;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;
class ScalarTypeSchemaDefinitionProvider extends \PoPAPI\API\ObjectModels\SchemaDefinition\AbstractNamedTypeSchemaDefinitionProvider
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface
     */
    protected $scalarTypeResolver;
    public function __construct(ScalarTypeResolverInterface $scalarTypeResolver)
    {
        $this->scalarTypeResolver = $scalarTypeResolver;
        parent::__construct($scalarTypeResolver);
    }
    public function getTypeKind() : string
    {
        return TypeKinds::SCALAR;
    }
    /**
     * @return array<string,mixed>
     */
    public function getSchemaDefinition() : array
    {
        $schemaDefinition = parent::getSchemaDefinition();
        $this->addScalarSchemaDefinition($schemaDefinition);
        return $schemaDefinition;
    }
    /**
     * @param array<string,mixed> $schemaDefinition
     */
    protected final function addScalarSchemaDefinition(&$schemaDefinition) : void
    {
        if ($specifiedByURL = $this->scalarTypeResolver->getSpecifiedByURL()) {
            $schemaDefinition[SchemaDefinition::SPECIFIED_BY_URL] = $specifiedByURL;
        }
    }
}
