<?php

declare (strict_types=1);
namespace PoP\ComponentModel\RelationalTypeResolverDecorators;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
interface RelationalTypeResolverDecoratorInterface extends AttachableExtensionInterface
{
    /**
     * The classes of the RelationalTypeResolvers this RelationalTypeResolverDecorator decorates.
     *
     * It is RelationalType and not ObjectType because directives can be applied on
     * the UnionTypeResolver too, and the RelationalTypeResolverDecorator will deal with
     * the IFTTT rules for those directives.
     *
     * The list can contain both concrete and abstract classes (in which case all classes
     * extending from them will be selected)
     *
     * @return array<class-string<RelationalTypeResolverInterface>>
     */
    public function getRelationalTypeResolverClassesToAttachTo() : array;
    /**
     * Allow to disable the functionality
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function enabled($relationalTypeResolver) : bool;
    /**
     * Return an array of fieldNames as keys, and, for each fieldName, an array of directives (including directive arguments) to be applied always on the field
     *
     * @return array<string,Directive[]> Key: fieldName or "*" (for any field), Value: List of Directives
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     */
    public function getMandatoryDirectivesForFields($objectTypeResolver) : array;
    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied before
     *
     * @return array<string,Directive[]> Key: directiveName, Value: List of Directives
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getPrecedingMandatoryDirectivesForDirectives($relationalTypeResolver) : array;
    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied after
     *
     * @return array<string,Directive[]> Key: directiveName, Value: List of Directives
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getSucceedingMandatoryDirectivesForDirectives($relationalTypeResolver) : array;
}
