<?php

declare (strict_types=1);
namespace PoPSchema\Pages\FieldResolvers;

use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\Pages\ComponentConfiguration;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\Pages\TypeResolvers\PageTypeResolver;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
class RootPageFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoP\Engine\TypeResolvers\RootTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['page', 'pages', 'pageCount'];
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['page' => $translationAPI->__('Page with a specific ID', 'pages'), 'pages' => $translationAPI->__('Pages', 'pages'), 'pageCount' => $translationAPI->__('Number of pages', 'pages')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['page' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, 'pages' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID), 'pageCount' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_INT];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $nonNullableFieldNames = ['pages', 'pageCount'];
        if (\in_array($fieldName, $nonNullableFieldNames)) {
            return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'page':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'id', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The page ID', 'pages'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
            case 'pages':
            case 'pageCount':
                return \array_merge($schemaFieldArgs, $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName));
        }
        return $schemaFieldArgs;
    }
    public function enableOrderedSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        switch ($fieldName) {
            case 'pages':
            case 'pageCount':
                return \false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }
    protected function getFieldDefaultFilterDataloadingModule(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : ?array
    {
        switch ($fieldName) {
            case 'pageCount':
                return [\PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor::class, \PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTCOUNT];
        }
        return parent::getFieldDefaultFilterDataloadingModule($typeResolver, $fieldName, $fieldArgs);
    }
    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     * @param object $resultItem
     */
    public function resolveValue(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $pageTypeAPI = \PoPSchema\Pages\Facades\PageTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'page':
                $query = ['include' => [$fieldArgs['id']], 'status' => [\PoPSchema\CustomPosts\Types\Status::PUBLISHED]];
                $options = ['return-type' => \PoPSchema\SchemaCommons\DataLoading\ReturnTypes::IDS];
                if ($pages = $pageTypeAPI->getPages($query, $options)) {
                    return $pages[0];
                }
                return null;
            case 'pages':
                $query = ['limit' => \PoPSchema\Pages\ComponentConfiguration::getPageListDefaultLimit(), 'status' => [\PoPSchema\CustomPosts\Types\Status::PUBLISHED]];
                $options = ['return-type' => \PoPSchema\SchemaCommons\DataLoading\ReturnTypes::IDS];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $pageTypeAPI->getPages($query, $options);
            case 'pageCount':
                $query = ['status' => [\PoPSchema\CustomPosts\Types\Status::PUBLISHED]];
                $options = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $pageTypeAPI->getPageCount($query, $options);
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'page':
            case 'pages':
                return \PoPSchema\Pages\TypeResolvers\PageTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
