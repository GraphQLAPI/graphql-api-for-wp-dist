<?php

declare (strict_types=1);
namespace PoPSchema\CustomPosts\FieldResolvers;

use PoPSchema\CustomPosts\ComponentConfiguration;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
abstract class AbstractCustomPostListFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver
{
    public static function getFieldNamesToResolve() : array
    {
        return ['customPosts', 'customPostCount'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['customPosts' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID), 'customPostCount' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_INT];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostCount':
                return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['customPosts' => $translationAPI->__('Custom posts', 'pop-posts'), 'customPostCount' => $translationAPI->__('Number of custom posts', 'pop-posts')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostCount':
                return \array_merge($schemaFieldArgs, $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName));
        }
        return $schemaFieldArgs;
    }
    public function enableOrderedSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostCount':
                return \false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }
    protected function getFieldDefaultFilterDataloadingModule(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : ?array
    {
        switch ($fieldName) {
            case 'customPostCount':
                return [\PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor::class, \PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTCOUNT];
        }
        return parent::getFieldDefaultFilterDataloadingModule($typeResolver, $fieldName, $fieldArgs);
    }
    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     * @param object $resultItem
     */
    protected function getQuery(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []) : array
    {
        switch ($fieldName) {
            case 'customPosts':
                return ['limit' => \PoPSchema\CustomPosts\ComponentConfiguration::getCustomPostListDefaultLimit(), 'types-from-union-resolver-class' => \PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver::class, 'status' => [\PoPSchema\CustomPosts\Types\Status::PUBLISHED]];
            case 'customPostCount':
                return ['types-from-union-resolver-class' => \PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver::class, 'status' => [\PoPSchema\CustomPosts\Types\Status::PUBLISHED]];
        }
        return [];
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
        $customPostTypeAPI = \PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'customPosts':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = ['return-type' => \PoPSchema\SchemaCommons\DataLoading\ReturnTypes::IDS];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $customPostTypeAPI->getCustomPosts($query, $options);
            case 'customPostCount':
                $query = $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
                $options = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $customPostTypeAPI->getCustomPostCount($query, $options);
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'customPosts':
                return \PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetTypeResolverClass(\PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver::class);
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
