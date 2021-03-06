<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ConditionalOnEnvironment\VariablesAsExpressions\SchemaServices\FieldResolvers;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use GraphQLByPoP\GraphQLServer\ConditionalOnEnvironment\MultipleQueryExecution\SchemaServices\DirectiveResolvers\ExportDirectiveResolver;
use GraphQLByPoP\GraphQLQuery\Facades\GraphQLQueryConvertorFacade;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
class VariablesAsExpressionsRootFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoP\Engine\TypeResolvers\RootTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return [
            'exportedVariables',
            // 'exportedVariable',
            'echoVar',
        ];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = [
            'exportedVariables' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED),
            // 'exportedVariable' => SchemaDefinition::TYPE_MIXED,
            'echoVar' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $nonNullableFieldNames = ['exportedVariables'];
        if (\in_array($fieldName, $nonNullableFieldNames)) {
            return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $exportDirectiveName = \GraphQLByPoP\GraphQLServer\ConditionalOnEnvironment\MultipleQueryExecution\SchemaServices\DirectiveResolvers\ExportDirectiveResolver::getDirectiveName();
        $descriptions = [
            'exportedVariables' => \sprintf($translationAPI->__('Returns a dictionary with the values for all variables exported through the `%s` directive', 'graphql-server'), $exportDirectiveName),
            // 'exportedVariable' => sprintf(
            //     $translationAPI->__('Returns the value for a variable exported through the `%s` directive', 'graphql-server'),
            //     $exportDirectiveName
            // ),
            'echoVar' => $translationAPI->__('Returns the variable value', 'graphql-server'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            // case 'exportedVariable':
            //     return array_merge(
            //         $schemaFieldArgs,
            //         [
            //             [
            //                 SchemaDefinition::ARGNAME_NAME => 'name',
            //                 SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
            //                 SchemaDefinition::ARGNAME_DESCRIPTION => sprintf(
            //                     $translationAPI->__('Exported variable name. It must start with \'%s\'', 'graphql-server'),
            //                     QuerySymbols::VARIABLE_AS_EXPRESSION_NAME_PREFIX
            //                 ),
            //                 SchemaDefinition::ARGNAME_MANDATORY => true,
            //             ],
            //         ]
            //     );
            case 'echoVar':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'variable', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The variable to echo back, of any type', 'graphql-server'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
        }
        return $schemaFieldArgs;
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
        $graphQLQueryConvertor = \GraphQLByPoP\GraphQLQuery\Facades\GraphQLQueryConvertorFacade::getInstance();
        switch ($fieldName) {
            case 'exportedVariables':
                // All the variables starting with "_" are treated as expressions
                return \array_filter($variables ?? [], function ($variableName) use($graphQLQueryConvertor) {
                    return $graphQLQueryConvertor->treatVariableAsExpression($variableName);
                }, \ARRAY_FILTER_USE_KEY);
            // case 'exportedVariable':
            //     if ($variables) {
            //         return $variables[$fieldArgs['name']];
            //     }
            //     return null;
            case 'echoVar':
                return $fieldArgs['variable'];
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
