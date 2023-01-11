<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Schema;

interface SchemaNamespacingServiceInterface
{
    /**
     * @param string $classOwnerAndProjectNamespace
     * @param string $schemaNamespace
     */
    public function addSchemaNamespaceForClassOwnerAndProjectNamespace($classOwnerAndProjectNamespace, $schemaNamespace) : void;
    /**
     * @param string $class
     */
    public function getSchemaNamespace($class) : string;
    /**
     * @param string $schemaNamespace
     * @param string $name
     */
    public function getSchemaNamespacedName($schemaNamespace, $name) : string;
}
