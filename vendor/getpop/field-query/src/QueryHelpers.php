<?php

declare (strict_types=1);
namespace PoP\FieldQuery;

use PoP\QueryParsing\Facades\QueryParserFacade;
class QueryHelpers
{
    /**
     * @return array<int|false>
     */
    public static function listFieldArgsSymbolPositions(string $field) : array
    {
        return [\PoP\FieldQuery\QueryUtils::findFirstSymbolPosition($field, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_OPENING, [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING]), \PoP\FieldQuery\QueryUtils::findLastSymbolPosition($field, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_CLOSING, [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING])];
    }
    /**
     * @return array<int|false>
     */
    public static function listFieldBookmarkSymbolPositions(string $field) : array
    {
        return [\PoP\FieldQuery\QueryUtils::findFirstSymbolPosition($field, \PoP\FieldQuery\QuerySyntax::SYMBOL_BOOKMARK_OPENING, [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_CLOSING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING]), \PoP\FieldQuery\QueryUtils::findLastSymbolPosition($field, \PoP\FieldQuery\QuerySyntax::SYMBOL_BOOKMARK_CLOSING, [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_CLOSING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING])];
    }
    /**
     * @return int|bool
     */
    public static function findFieldAliasSymbolPosition(string $field)
    {
        return \PoP\FieldQuery\QueryUtils::findFirstSymbolPosition($field, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDALIAS_PREFIX, [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_CLOSING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING]);
    }
    /**
     * @return int|bool
     */
    public static function findSkipOutputIfNullSymbolPosition(string $field)
    {
        return \PoP\FieldQuery\QueryUtils::findFirstSymbolPosition($field, \PoP\FieldQuery\QuerySyntax::SYMBOL_SKIPOUTPUTIFNULL, [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_BOOKMARK_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_CLOSING, \PoP\FieldQuery\QuerySyntax::SYMBOL_BOOKMARK_CLOSING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING]);
    }
    /**
     * @return array<int|false>
     */
    public static function listFieldDirectivesSymbolPositions(string $field) : array
    {
        return [\PoP\FieldQuery\QueryUtils::findFirstSymbolPosition($field, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_CLOSING), \PoP\FieldQuery\QueryUtils::findLastSymbolPosition($field, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_CLOSING)];
    }
    public static function getEmptyFieldArgs() : string
    {
        return \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_OPENING . \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_CLOSING;
    }
    /**
     * @return string[]
     */
    public static function getFieldArgElements(?string $fieldArgsAsString) : array
    {
        if ($fieldArgsAsString) {
            // Remove the opening and closing brackets
            $fieldArgsAsString = \substr($fieldArgsAsString, \strlen(\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_OPENING), \strlen($fieldArgsAsString) - \strlen(\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_OPENING) - \strlen(\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_CLOSING));
            // Remove the white spaces before and after
            $fieldArgsAsString = \trim($fieldArgsAsString);
            // Use `strlen` to allow for "0" as value. Eg: <skip(0)> meaning false
            if (!empty($fieldArgsAsString) || \strlen($fieldArgsAsString)) {
                $queryParser = QueryParserFacade::getInstance();
                return $queryParser->splitElements($fieldArgsAsString, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_ARGSEPARATOR, [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_OPENING], [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_CLOSING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_ARGVALUEARRAY_CLOSING], \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
            }
        }
        return [];
    }
    public static function getVariableQuery(string $variableName) : string
    {
        return \PoP\FieldQuery\QuerySyntax::SYMBOL_VARIABLE_PREFIX . $variableName;
    }
    public static function getExpressionQuery(string $expressionName) : string
    {
        return \PoP\FieldQuery\QuerySyntax::SYMBOL_EXPRESSION_OPENING . $expressionName . \PoP\FieldQuery\QuerySyntax::SYMBOL_EXPRESSION_CLOSING;
    }
    /**
     * @return string[]
     */
    public static function splitFieldDirectives(string $fieldDirectives) : array
    {
        $queryParser = QueryParserFacade::getInstance();
        return $queryParser->splitElements($fieldDirectives, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_SEPARATOR, [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_BOOKMARK_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING], [\PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_CLOSING, \PoP\FieldQuery\QuerySyntax::SYMBOL_BOOKMARK_CLOSING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING], \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_OPENING, \PoP\FieldQuery\QuerySyntax::SYMBOL_FIELDARGS_ARGVALUESTRING_CLOSING);
    }
}
