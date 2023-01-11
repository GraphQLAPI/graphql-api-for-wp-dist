<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Tracing;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
class Trace implements \PoP\ComponentModel\Tracing\TraceInterface
{
    /**
     * @var string|int
     */
    protected $id;
    /**
     * @var array<string, mixed>
     */
    protected $data = [];
    /**
     * @var \PoP\GraphQLParser\Spec\Parser\Ast\Directive|null
     */
    protected $directive;
    /**
     * @var mixed[]|null
     */
    protected $idFields;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface|null
     */
    protected $relationalTypeResolver;
    /**
     * @var \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface|null
     */
    protected $field;
    /**
     * @var string|int|null
     */
    protected $objectID = null;
    /**
     * @param array<string,mixed> $data
     * @param string|int $id
     * @param string|int|null $objectID
     */
    public function __construct($id, array $data = [], ?Directive $directive = null, ?array $idFields = null, ?RelationalTypeResolverInterface $relationalTypeResolver = null, ?FieldInterface $field = null, $objectID = null)
    {
        $this->id = $id;
        /** @var array<string,mixed> */
        $this->data = $data;
        $this->directive = $directive;
        /** @var array<string|int,FieldInterface[]>|null */
        $this->idFields = $idFields;
        $this->relationalTypeResolver = $relationalTypeResolver;
        $this->field = $field;
        $this->objectID = $objectID;
    }
    /**
     * @return string|int
     */
    public function getID()
    {
        return $this->id;
    }
    /**
     * @return array<string,mixed>
     */
    public function getData() : array
    {
        return $this->data;
    }
    public function getDirective() : ?Directive
    {
        return $this->directive;
    }
    /**
     * @return array<string|int,FieldInterface[]>|null
     */
    public function getIDFields() : ?array
    {
        return $this->idFields;
    }
    public function getRelationalTypeResolver() : ?RelationalTypeResolverInterface
    {
        return $this->relationalTypeResolver;
    }
    public function getField() : ?FieldInterface
    {
        return $this->field;
    }
    /**
     * @return string|int|null
     */
    public function getObjectID()
    {
        return $this->objectID;
    }
}
