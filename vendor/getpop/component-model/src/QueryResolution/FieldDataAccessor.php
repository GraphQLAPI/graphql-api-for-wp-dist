<?php

declare (strict_types=1);
namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
class FieldDataAccessor implements \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface
{
    use \PoP\ComponentModel\QueryResolution\FieldOrDirectiveDataAccessorTrait;
    /**
     * @var \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface
     */
    protected $field;
    /**
     * @var array<string, mixed>
     */
    protected $unresolvedFieldArgs;
    /**
     * @param array<string,mixed> $unresolvedFieldArgs
     */
    public function __construct(FieldInterface $field, array $unresolvedFieldArgs)
    {
        $this->field = $field;
        /** @var array<string,mixed> */
        $this->unresolvedFieldArgs = $unresolvedFieldArgs;
    }
    public function getField() : FieldInterface
    {
        return $this->field;
    }
    public final function getFieldName() : string
    {
        return $this->field->getName();
    }
    /**
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    public function getFieldArgs() : array
    {
        return $this->getResolvedFieldOrDirectiveArgs();
    }
    /**
     * @return array<string,mixed>
     */
    protected function getUnresolvedFieldOrDirectiveArgs() : array
    {
        return $this->unresolvedFieldArgs;
    }
    /**
     * When the Args contain a "Resolved on Object" Promise,
     * then caching the results will not work across objects,
     * and the cache must then be explicitly cleared.
     */
    public function resetFieldArgs() : void
    {
        $this->resetResolvedFieldOrDirectiveArgs();
    }
}
