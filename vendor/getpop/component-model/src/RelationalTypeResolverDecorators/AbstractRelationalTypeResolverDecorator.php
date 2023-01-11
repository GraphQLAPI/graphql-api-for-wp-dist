<?php

declare (strict_types=1);
namespace PoP\ComponentModel\RelationalTypeResolverDecorators;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\Root\Services\BasicServiceTrait;
abstract class AbstractRelationalTypeResolverDecorator implements \PoP\ComponentModel\RelationalTypeResolverDecorators\RelationalTypeResolverDecoratorInterface
{
    use AttachableExtensionTrait;
    use BasicServiceTrait;
    /**
     * @var \PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface|null
     */
    private $attachableExtensionManager;
    /**
     * @param \PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface $attachableExtensionManager
     */
    public final function setAttachableExtensionManager($attachableExtensionManager) : void
    {
        $this->attachableExtensionManager = $attachableExtensionManager;
    }
    protected final function getAttachableExtensionManager() : AttachableExtensionManagerInterface
    {
        /** @var AttachableExtensionManagerInterface */
        return $this->attachableExtensionManager = $this->attachableExtensionManager ?? $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
    }
    /**
     * @return string[] Either the class, or the constant "*" to represent _any_ class
     */
    public final function getClassesToAttachTo() : array
    {
        return $this->getRelationalTypeResolverClassesToAttachTo();
    }
    /**
     * Allow to disable the functionality
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function enabled($relationalTypeResolver) : bool
    {
        return \true;
    }
    /**
     * Return an array of fieldNames as keys, and, for each fieldName, an array of directives (including directive arguments) to be applied always on the field
     *
     * @return array<string,Directive[]> Key: fieldName or "*" (for any field), Value: List of Directives
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     */
    public function getMandatoryDirectivesForFields($objectTypeResolver) : array
    {
        return [];
    }
    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied before
     *
     * @return array<string,Directive[]>
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getPrecedingMandatoryDirectivesForDirectives($relationalTypeResolver) : array
    {
        return [];
    }
    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied after
     *
     * @return array<string,Directive[]> Key: directiveName, Value: List of Directives
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public function getSucceedingMandatoryDirectivesForDirectives($relationalTypeResolver) : array
    {
        return [];
    }
}
