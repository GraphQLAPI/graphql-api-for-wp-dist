<?php

declare (strict_types=1);
namespace PoP\Engine\TypeResolvers;

use PoP\Engine\ObjectModels\Root;
use PoP\Engine\TypeDataLoaders\RootTypeDataLoader;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
class RootTypeResolver extends \PoP\ComponentModel\TypeResolvers\AbstractTypeResolver
{
    use ReservedNameTypeResolverTrait;
    public const NAME = 'Root';
    public function getTypeName() : string
    {
        return self::NAME;
    }
    public function getSchemaTypeDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Root type, starting from which the query is executed', 'api');
    }
    /**
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        /** @var Root */
        $root = $resultItem;
        return $root->getID();
    }
    public function getTypeDataLoaderClass() : string
    {
        return \PoP\Engine\TypeDataLoaders\RootTypeDataLoader::class;
    }
    protected function addSchemaDefinition(array $stackMessages, array &$generalMessages, array $options = [])
    {
        parent::addSchemaDefinition($stackMessages, $generalMessages, $options);
        // Only in the root we output the operators and helpers
        $schemaDefinitionService = \PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade::getInstance();
        $typeSchemaKey = $schemaDefinitionService->getTypeSchemaKey($this);
        // Add the directives (global)
        $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES] = [];
        $schemaDirectiveResolvers = $this->getSchemaDirectiveResolvers(\true);
        foreach ($schemaDirectiveResolvers as $directiveName => $directiveResolver) {
            $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES][$directiveName] = $this->getDirectiveSchemaDefinition($directiveResolver, $options);
        }
        // Add the fields (global)
        $schemaFieldResolvers = $this->getSchemaFieldResolvers(\true);
        foreach ($schemaFieldResolvers as $fieldName => $fieldResolver) {
            $this->addFieldSchemaDefinition($fieldResolver, $fieldName, $stackMessages, $generalMessages, $options);
        }
    }
}
