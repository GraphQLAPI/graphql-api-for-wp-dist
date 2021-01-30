<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations;
use PoP\ComponentModel\Directives\DirectiveTypes;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasArgsSchemaDefinitionReferenceTrait;
class Directive extends \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject
{
    use HasArgsSchemaDefinitionReferenceTrait;
    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath, array $customDefinition = [])
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath, $customDefinition);
        $this->initArgs($fullSchemaDefinition, $schemaDefinitionPath);
        $this->initializeArgsTypeDependencies();
    }
    public function getName() : string
    {
        return $this->schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME];
    }
    public function getDescription() : ?string
    {
        return $this->schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION];
    }
    public function getLocations() : array
    {
        $directives = [];
        $directiveType = $this->schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVE_TYPE];
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        /**
         * There are 2 cases for adding the "Query" type locations:
         * 1. When the type is "Query"
         * 2. When the type is "Schema" and we are editing the query on the back-end (as to replace the lack of SDL)
         */
        if ($directiveType == \PoP\ComponentModel\Directives\DirectiveTypes::QUERY || $directiveType == \PoP\ComponentModel\Directives\DirectiveTypes::SCHEMA && isset($vars['edit-schema']) && $vars['edit-schema']) {
            // Same DirectiveLocations as used by "@skip": https://graphql.github.io/graphql-spec/draft/#sec--skip
            $directives = \array_merge($directives, [\GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::FIELD, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::FRAGMENT_SPREAD, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::INLINE_FRAGMENT]);
        }
        if ($directiveType == \PoP\ComponentModel\Directives\DirectiveTypes::SCHEMA) {
            $directives = \array_merge($directives, [\GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::FIELD_DEFINITION]);
        }
        return $directives;
    }
    public function isRepeatable() : bool
    {
        return $this->schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVE_IS_REPEATABLE];
    }
}
