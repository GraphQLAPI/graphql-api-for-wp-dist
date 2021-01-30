<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Environment;
trait WithVersionConstraintFieldOrDirectiveResolverTrait
{
    protected function getVersionConstraintSchemaFieldOrDirectiveArg() : array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_VERSION_CONSTRAINT, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The version to restrict to, using the semantic versioning constraint rules used by Composer (https://getcomposer.org/doc/articles/versions.md)', 'component-model')];
    }
    /**
     * If enabled, add the "versionConstraint" param. Add it at the end, so it doesn't affect the order of params for "orderedSchemaDirectiveArgs"
     *
     * @param array $schemaDirectiveArgs
     * @param string|null $version the version of the fieldResolver/directiveResolver
     * @return void
     */
    protected function maybeAddVersionConstraintSchemaFieldOrDirectiveArg(array &$schemaFieldOrDirectiveArgs, bool $hasVersion) : void
    {
        // Only add the argument if this field or directive has a version
        // If it doesn't, then there will only be one version of it, and it can be kept empty for simplicity
        if ($hasVersion && \PoP\ComponentModel\Environment::enableSemanticVersionConstraints()) {
            $schemaFieldOrDirectiveArgs[] = $this->getVersionConstraintSchemaFieldOrDirectiveArg();
        }
    }
}
