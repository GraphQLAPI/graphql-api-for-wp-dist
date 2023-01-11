<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Schema;

use PoP\Root\Helpers\ClassHelpers;
class SchemaNamespacingService implements \PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface
{
    /**
     * @var array<string,string>
     */
    protected $classOwnerAndProjectNamespaceSchemaNamespaces = [];
    /**
     * @param string $classOwnerAndProjectNamespace
     * @param string $schemaNamespace
     */
    public function addSchemaNamespaceForClassOwnerAndProjectNamespace($classOwnerAndProjectNamespace, $schemaNamespace) : void
    {
        $this->classOwnerAndProjectNamespaceSchemaNamespaces[$classOwnerAndProjectNamespace] = $schemaNamespace;
    }
    /**
     * @param string $class
     */
    public function getSchemaNamespace($class) : string
    {
        $classOwnerAndProjectNamespace = ClassHelpers::getClassPSR4Namespace($class);
        // Check if an entry for this combination of Owner + class has been provided
        if (isset($this->classOwnerAndProjectNamespaceSchemaNamespaces[$classOwnerAndProjectNamespace])) {
            return $this->classOwnerAndProjectNamespaceSchemaNamespaces[$classOwnerAndProjectNamespace];
        }
        return $this->convertClassNamespaceToSchemaNamespace($classOwnerAndProjectNamespace);
    }
    /**
     * @param string $classNamespace
     */
    protected function convertClassNamespaceToSchemaNamespace($classNamespace) : string
    {
        return \str_replace('\\', \PoP\ComponentModel\Schema\SchemaDefinitionTokens::NAMESPACE_SEPARATOR, $classNamespace);
    }
    /**
     * @param string $schemaNamespace
     * @param string $name
     */
    public function getSchemaNamespacedName($schemaNamespace, $name) : string
    {
        return ($schemaNamespace ? $schemaNamespace . \PoP\ComponentModel\Schema\SchemaDefinitionTokens::NAMESPACE_SEPARATOR : '') . $name;
    }
}
