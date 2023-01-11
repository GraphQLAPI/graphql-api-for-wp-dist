<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Tracing;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
interface TraceInterface
{
    /**
     * @return string|int
     */
    public function getID();
    /**
     * @return array<string,mixed>
     */
    public function getData() : array;
    /**
     * @return array<string|int,FieldInterface[]>|null
     */
    public function getIDFields() : ?array;
    public function getDirective() : ?Directive;
    public function getRelationalTypeResolver() : ?RelationalTypeResolverInterface;
    public function getField() : ?FieldInterface;
    /**
     * @return string|int|null
     */
    public function getObjectID();
}
