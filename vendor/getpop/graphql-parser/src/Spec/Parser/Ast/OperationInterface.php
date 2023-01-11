<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface OperationInterface extends \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface, \PoP\GraphQLParser\Spec\Parser\Ast\WithDirectivesInterface, \PoP\GraphQLParser\Spec\Parser\Ast\WithFieldsOrFragmentBondsInterface
{
    public function getName() : string;
    public function getOperationType() : string;
    /**
     * @return Variable[]
     */
    public function getVariables() : array;
}
