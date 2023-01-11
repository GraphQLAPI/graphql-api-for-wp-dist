<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithArgumentsInterface extends \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface
{
    public function hasArguments() : bool;
    /**
     * @param string $name
     */
    public function hasArgument($name) : bool;
    /**
     * @return Argument[]
     */
    public function getArguments() : array;
    /**
     * @param string $name
     */
    public function getArgument($name) : ?\PoP\GraphQLParser\Spec\Parser\Ast\Argument;
    /**
     * @return mixed
     * @param string $name
     */
    public function getArgumentValue($name);
    /**
     * @return array<string,mixed>
     */
    public function getArgumentKeyValues() : array;
    /**
     * Indicate if any of the Arguments contains a Promise
     */
    public function hasArgumentReferencingPromise() : bool;
    /**
     * Indicate if any of the Promises must be resolved on the Object
     */
    public function hasArgumentReferencingResolvedOnObjectPromise() : bool;
}
