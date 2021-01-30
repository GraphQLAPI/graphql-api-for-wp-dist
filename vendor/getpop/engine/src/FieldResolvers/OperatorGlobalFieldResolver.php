<?php

declare (strict_types=1);
namespace PoP\Engine\FieldResolvers;

use PoP\Engine\Misc\Extract;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractGlobalFieldResolver;
class OperatorGlobalFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractGlobalFieldResolver
{
    /**
     * @var array<string, mixed>
     */
    protected $safeVars = null;
    public const HOOK_SAFEVARS = __CLASS__ . ':safeVars';
    public static function getFieldNamesToResolve() : array
    {
        return ['if', 'not', 'and', 'or', 'equals', 'empty', 'isNull', 'var', 'context', 'extract', 'time', 'echo', 'sprintf'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['if' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED, 'not' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, 'and' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, 'or' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, 'equals' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, 'empty' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, 'isNull' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, 'var' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED, 'context' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_OBJECT, 'extract' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED, 'time' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_INT, 'echo' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED, 'sprintf' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        switch ($fieldName) {
            case 'not':
            case 'and':
            case 'or':
            case 'equals':
            case 'empty':
            case 'isNull':
            case 'context':
            case 'time':
            case 'sprintf':
                return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['if' => $translationAPI->__('If a boolean property is true, execute a field, else, execute another field', 'component-model'), 'not' => $translationAPI->__('Return the opposite value of a boolean property', 'component-model'), 'and' => $translationAPI->__('Return an `AND` operation among several boolean properties', 'component-model'), 'or' => $translationAPI->__('Return an `OR` operation among several boolean properties', 'component-model'), 'equals' => $translationAPI->__('Indicate if the result from a field equals a certain value', 'component-model'), 'empty' => $translationAPI->__('Indicate if a value is empty', 'component-model'), 'isNull' => $translationAPI->__('Indicate if a value is null', 'component-model'), 'var' => $translationAPI->__('Retrieve the value of a certain property from the `$vars` context object', 'component-model'), 'context' => $translationAPI->__('Retrieve the `$vars` context object', 'component-model'), 'extract' => $translationAPI->__('Given an object, it retrieves the data under a certain path', 'pop-component-model'), 'time' => $translationAPI->__('Return the time now (https://php.net/manual/en/function.time.php)', 'component-model'), 'echo' => $translationAPI->__('Repeat back the input, whatever it is', 'function-fields'), 'sprintf' => $translationAPI->__('Replace placeholders inside a string with provided values', 'function-fields')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'if':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'condition', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The condition to check if its value is `true` or `false`', 'component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'then', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The value to return if the condition evals to `true`', 'component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'else', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The value to return if the condition evals to `false`', 'component-model')]]);
            case 'not':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'value', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The value from which to return its opposite value', 'component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
            case 'and':
            case 'or':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'values', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => \sprintf($translationAPI->__('The array of values on which to execute the `%s` operation', 'component-model'), \strtoupper($fieldName)), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
            case 'equals':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'value1', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The first value to compare', 'component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'value2', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The second value to compare', 'component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
            case 'empty':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'value', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The value to check if it is empty', 'component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
            case 'isNull':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'value', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The value to check if it is null', 'component-model')]]);
            case 'var':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'name', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The name of the variable to retrieve from the `$vars` context object', 'component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
            case 'extract':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'object', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_OBJECT, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The object to retrieve the data from', 'pop-component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'path', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The path to retrieve data from the object. Paths are separated with \'.\' for each sublevel', 'pop-component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
            case 'echo':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'value', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The input to be echoed back', 'function-fields'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
            case 'sprintf':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'string', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The string containing the placeholders', 'function-fields'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'values', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The values to replace the placeholders with inside the string', 'function-fields'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
        }
        return $schemaFieldArgs;
    }
    public function resolveSchemaValidationErrorDescriptions(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : ?array
    {
        if ($errors = parent::resolveSchemaValidationErrorDescriptions($typeResolver, $fieldName, $fieldArgs)) {
            return $errors;
        }
        // Important: The validations below can only be done if no fieldArg contains a field!
        // That is because this is a schema error, so we still don't have the $resultItem against which to resolve the field
        // For instance, this doesn't work: /?query=arrayItem(posts(),3)
        // In that case, the validation will be done inside ->resolveValue(), and will be treated as a $dbError, not a $schemaError
        if (!\PoP\ComponentModel\Schema\FieldQueryUtils::isAnyFieldArgumentValueAField($fieldArgs)) {
            $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
            switch ($fieldName) {
                case 'var':
                    $safeVars = $this->getSafeVars();
                    if (!isset($safeVars[$fieldArgs['name']])) {
                        return [\sprintf($translationAPI->__('Var \'%s\' does not exist in `$vars`', 'component-model'), $fieldArgs['name'])];
                    }
                    return null;
            }
        }
        return null;
    }
    protected function getSafeVars()
    {
        if (\is_null($this->safeVars)) {
            $this->safeVars = \PoP\ComponentModel\State\ApplicationState::getVars();
            \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->doAction(self::HOOK_SAFEVARS, array(&$this->safeVars));
        }
        return $this->safeVars;
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
        switch ($fieldName) {
            case 'if':
                if ($fieldArgs['condition']) {
                    return $fieldArgs['then'];
                } elseif (isset($fieldArgs['else'])) {
                    return $fieldArgs['else'];
                }
                return null;
            case 'not':
                return !$fieldArgs['value'];
            case 'and':
                return \array_reduce($fieldArgs['values'], function ($accumulated, $value) {
                    $accumulated = $accumulated && $value;
                    return $accumulated;
                }, \true);
            case 'or':
                return \array_reduce($fieldArgs['values'], function ($accumulated, $value) {
                    $accumulated = $accumulated || $value;
                    return $accumulated;
                }, \false);
            case 'equals':
                return $fieldArgs['value1'] == $fieldArgs['value2'];
            case 'empty':
                return empty($fieldArgs['value']);
            case 'isNull':
                return \is_null($fieldArgs['value']);
            case 'var':
                $safeVars = $this->getSafeVars();
                return $safeVars[$fieldArgs['name']];
            case 'context':
                return $this->getSafeVars();
            case 'extract':
                return \PoP\Engine\Misc\Extract::getDataFromPath($fieldName, $fieldArgs['object'], $fieldArgs['path']);
            case 'time':
                return \time();
            case 'echo':
                return $fieldArgs['value'];
            case 'sprintf':
                return \sprintf($fieldArgs['string'], ...$fieldArgs['values']);
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
