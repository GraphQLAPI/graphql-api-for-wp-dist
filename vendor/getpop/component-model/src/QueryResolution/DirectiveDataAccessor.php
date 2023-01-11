<?php

declare (strict_types=1);
namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;
class DirectiveDataAccessor implements \PoP\ComponentModel\QueryResolution\DirectiveDataAccessorInterface
{
    use \PoP\ComponentModel\QueryResolution\FieldOrDirectiveDataAccessorTrait;
    /**
     * @var array<string, mixed>
     */
    protected $unresolvedDirectiveArgs;
    /**
     * @param array<string,mixed> $unresolvedDirectiveArgs
     */
    public function __construct(array $unresolvedDirectiveArgs)
    {
        /** @var array<string,mixed> */
        $this->unresolvedDirectiveArgs = $unresolvedDirectiveArgs;
    }
    /**
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     */
    public function getDirectiveArgs() : array
    {
        return $this->getResolvedFieldOrDirectiveArgs();
    }
    /**
     * @return array<string,mixed>
     */
    protected function getUnresolvedFieldOrDirectiveArgs() : array
    {
        return $this->unresolvedDirectiveArgs;
    }
    /**
     * When the Args contain a "Resolved on Object" Promise,
     * then caching the results will not work across objects,
     * and the cache must then be explicitly cleared.
     */
    public function resetDirectiveArgs() : void
    {
        $this->resetResolvedFieldOrDirectiveArgs();
    }
}
