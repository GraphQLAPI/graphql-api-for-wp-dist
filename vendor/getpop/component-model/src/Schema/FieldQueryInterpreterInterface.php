<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
interface FieldQueryInterpreterInterface extends \PoP\FieldQuery\FieldQueryInterpreterInterface
{
    /**
     * Extract field args without using the schema. It is needed to find out which fieldResolver will process a field, where we can't depend on the schema since this one needs to know who the fieldResolver is, creating an infitine loop
     *
     * @param string $field
     * @param array|null $variables
     * @return array
     */
    public function extractStaticFieldArguments(string $field, ?array $variables = null) : array;
    public function extractStaticDirectiveArguments(string $directive, ?array $variables = null) : array;
    public function extractFieldArguments(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $field, ?array $variables = null, ?array &$schemaWarnings = null) : array;
    public function extractDirectiveArguments(\PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface $directiveResolver, \PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $directive, ?array $variables = null, ?array &$schemaWarnings = null) : array;
    public function extractFieldArgumentsForSchema(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $field, ?array $variables = null) : array;
    public function extractDirectiveArgumentsForSchema(\PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface $directiveResolver, \PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $directive, ?array $variables = null, bool $disableDynamicFields = \false) : array;
    /**
     * @param object $resultItem
     */
    public function extractFieldArgumentsForResultItem(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $field, ?array $variables, ?array $expressions) : array;
    /**
     * @param object $resultItem
     */
    public function extractDirectiveArgumentsForResultItem(\PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface $directiveResolver, \PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $directive, array $variables, array $expressions) : array;
    public function maybeConvertFieldArgumentValue($fieldArgValue, ?array $variables = null);
    public function maybeConvertFieldArgumentArrayValue($fieldArgValue, ?array $variables = null);
}
