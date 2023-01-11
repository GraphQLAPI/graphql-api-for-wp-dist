<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithValueInterface extends \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface
{
    /**
     * @return mixed
     */
    public function getValue();
}
