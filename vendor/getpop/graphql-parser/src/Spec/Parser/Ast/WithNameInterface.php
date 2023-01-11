<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithNameInterface extends \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface
{
    public function getName() : string;
}
