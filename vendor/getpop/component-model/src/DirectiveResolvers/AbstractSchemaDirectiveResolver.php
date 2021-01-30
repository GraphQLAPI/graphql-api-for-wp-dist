<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
abstract class AbstractSchemaDirectiveResolver extends \PoP\ComponentModel\DirectiveResolvers\AbstractDirectiveResolver implements \PoP\ComponentModel\DirectiveResolvers\SchemaDirectiveResolverInterface
{
    use WithVersionConstraintFieldOrDirectiveResolverTrait;
    public function getSchemaDefinitionResolver(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?\PoP\ComponentModel\DirectiveResolvers\SchemaDirectiveResolverInterface
    {
        return $this;
    }
    public function getSchemaDirectiveDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        return null;
    }
    public function getSchemaDirectiveWarningDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        return null;
    }
    public function getSchemaDirectiveDeprecationDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?string
    {
        return null;
    }
    public function getSchemaDirectiveExpressions(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        return [];
    }
    public function getSchemaDirectiveArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        return [];
    }
    public function getFilteredSchemaDirectiveArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        $schemaDirectiveArgs = $this->getSchemaDirectiveArgs($typeResolver);
        $this->maybeAddVersionConstraintSchemaFieldOrDirectiveArg($schemaDirectiveArgs, !empty($this->getSchemaDirectiveVersion($typeResolver)));
        return $schemaDirectiveArgs;
    }
    public function enableOrderedSchemaDirectiveArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool
    {
        return \true;
    }
    public function isGlobal(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool
    {
        return \false;
    }
}
