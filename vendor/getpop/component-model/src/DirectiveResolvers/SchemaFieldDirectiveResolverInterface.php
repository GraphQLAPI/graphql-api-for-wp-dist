<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
interface SchemaFieldDirectiveResolverInterface
{
    /**
     * Description of the directive, to be output as documentation in the schema
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveDescription($relationalTypeResolver) : ?string;
    /**
     * Define Schema Directive Arguments
     *
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveArgNameTypeResolvers($relationalTypeResolver) : array;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getDirectiveArgDescription($relationalTypeResolver, $directiveArgName) : ?string;
    /**
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName);
    /**
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName) : int;
    /**
     * Invoke Schema Directive Arguments
     *
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getConsolidatedDirectiveArgNameTypeResolvers($relationalTypeResolver) : array;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getConsolidatedDirectiveArgDescription($relationalTypeResolver, $directiveArgName) : ?string;
    /**
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getConsolidatedDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName);
    /**
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param string $directiveArgName
     */
    public function getConsolidatedDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName) : ?int;
    /**
     * Indicate if the directive has been deprecated, why, when, and/or how it must be replaced
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getDirectiveDeprecationMessage($relationalTypeResolver) : ?string;
    /**
     * Indicate if the directive is global (i.e. it can be applied to all fields, for all typeResolvers)
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function isGlobal($relationalTypeResolver) : bool;
}
