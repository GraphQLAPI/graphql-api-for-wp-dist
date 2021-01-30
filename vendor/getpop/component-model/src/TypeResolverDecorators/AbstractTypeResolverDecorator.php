<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\TypeResolverDecorators\TypeResolverDecoratorInterface;
abstract class AbstractTypeResolverDecorator implements \PoP\ComponentModel\TypeResolverDecorators\TypeResolverDecoratorInterface
{
    /**
     * This class is attached to a TypeResolver
     */
    use AttachableExtensionTrait;
    /**
     * Allow to disable the functionality
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function enabled(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool
    {
        return \true;
    }
    /**
     * Return an array of fieldNames as keys, and, for each fieldName, an array of directives (including directive arguments) to be applied always on the field
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getMandatoryDirectivesForFields(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        return [];
    }
    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied before
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getPrecedingMandatoryDirectivesForDirectives(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        return [];
    }
    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied after
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getSucceedingMandatoryDirectivesForDirectives(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        return [];
    }
}
