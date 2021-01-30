<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
interface TypeResolverDecoratorInterface
{
    /**
     * Allow to disable the functionality
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function enabled(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool;
    /**
     * Return an array of fieldNames as keys, and, for each fieldName, an array of directives (including directive arguments) to be applied always on the field
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getMandatoryDirectivesForFields(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array;
    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied before
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getPrecedingMandatoryDirectivesForDirectives(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array;
    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied after
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getSucceedingMandatoryDirectivesForDirectives(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array;
}
