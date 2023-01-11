<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithAstValueInterface
{
    /**
     * @return mixed
     */
    public function getAstValue();
}
