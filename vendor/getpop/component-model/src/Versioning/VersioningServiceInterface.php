<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Versioning;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
interface VersioningServiceInterface
{
    /**
     * Indicates the version constraints for specific fields in the schema
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function getVersionConstraintsForField($objectTypeResolver, $field) : ?string;
    /**
     * Indicates the version constraints for specific directives in the schema
     * @param \PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface $directive
     */
    public function getVersionConstraintsForDirective($directive) : ?string;
}
