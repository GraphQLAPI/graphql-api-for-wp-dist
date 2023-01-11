<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\ExtendedSpec\Execution\ValueResolutionPromiseInterface;
use stdClass;
trait WithArgumentsTrait
{
    /** @var Argument[] */
    protected $arguments = [];
    /** @var array<string,Argument> Keep separate to validate that no 2 Arguments have same name */
    protected $nameArguments = [];
    /** @var array<string,mixed>|null */
    protected $argumentKeyValues;
    /**
     * @var bool|null
     */
    protected $hasArgumentReferencingPromise;
    /**
     * @var bool|null
     */
    protected $hasArgumentReferencingResolvedOnObjectPromise;
    public function hasArguments() : bool
    {
        return $this->arguments !== [];
    }
    /**
     * @param string $name
     */
    public function hasArgument($name) : bool
    {
        return \array_key_exists($name, $this->nameArguments);
    }
    /**
     * @return Argument[]
     */
    public function getArguments() : array
    {
        return $this->arguments;
    }
    /**
     * @param string $name
     */
    public function getArgument($name) : ?\PoP\GraphQLParser\Spec\Parser\Ast\Argument
    {
        return $this->nameArguments[$name] ?? null;
    }
    /**
     * @return mixed
     * @param string $name
     */
    public function getArgumentValue($name)
    {
        if ($argument = $this->getArgument($name)) {
            return $argument->getValue();
        }
        return null;
    }
    /**
     * @param Argument[] $arguments
     */
    protected function setArguments($arguments) : void
    {
        $this->argumentKeyValues = null;
        foreach ($arguments as $argument) {
            $this->arguments[] = $argument;
            $this->nameArguments[$argument->getName()] = $argument;
        }
    }
    /**
     * @return array<string,mixed>
     */
    public function getArgumentKeyValues() : array
    {
        if ($this->argumentKeyValues !== null) {
            return $this->argumentKeyValues;
        }
        $this->argumentKeyValues = [];
        foreach ($this->getArguments() as $argument) {
            $this->argumentKeyValues[$argument->getName()] = $argument->getValue();
        }
        return $this->argumentKeyValues;
    }
    public function hasArgumentReferencingPromise() : bool
    {
        if ($this->hasArgumentReferencingPromise === null) {
            $this->hasArgumentReferencingPromise = $this->doHasArgumentReferencingPromise($this->getArgumentKeyValues());
        }
        return $this->hasArgumentReferencingPromise;
    }
    /**
     * @param array<string,mixed> $values
     * @return mixed
     */
    protected function doHasArgumentReferencingPromise($values)
    {
        foreach ($values as $value) {
            if ($value instanceof ValueResolutionPromiseInterface) {
                return \true;
            }
            if (\is_array($value) && $this->doHasArgumentReferencingPromise($value)) {
                return \true;
            }
            if ($value instanceof stdClass && $this->doHasArgumentReferencingPromise((array) $value)) {
                return \true;
            }
        }
        return \false;
    }
    public function hasArgumentReferencingResolvedOnObjectPromise() : bool
    {
        if ($this->hasArgumentReferencingResolvedOnObjectPromise === null) {
            $this->hasArgumentReferencingResolvedOnObjectPromise = $this->doHasArgumentReferencingResolvedOnObjectPromise($this->getArgumentKeyValues());
        }
        return $this->hasArgumentReferencingResolvedOnObjectPromise;
    }
    /**
     * @param array<string,mixed> $values
     * @return mixed
     */
    protected function doHasArgumentReferencingResolvedOnObjectPromise($values)
    {
        foreach ($values as $value) {
            if ($value instanceof ValueResolutionPromiseInterface && $value->mustResolveOnObject()) {
                return \true;
            }
            if (\is_array($value) && $this->doHasArgumentReferencingResolvedOnObjectPromise($value)) {
                return \true;
            }
            if ($value instanceof stdClass && $this->doHasArgumentReferencingResolvedOnObjectPromise((array) $value)) {
                return \true;
            }
        }
        return \false;
    }
}
