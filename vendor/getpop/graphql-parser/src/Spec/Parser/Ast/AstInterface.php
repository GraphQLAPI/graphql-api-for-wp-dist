<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface AstInterface extends \PoP\GraphQLParser\Spec\Parser\Ast\LocatableInterface
{
    public function asQueryString() : string;
    public function asASTNodeString() : string;
}
