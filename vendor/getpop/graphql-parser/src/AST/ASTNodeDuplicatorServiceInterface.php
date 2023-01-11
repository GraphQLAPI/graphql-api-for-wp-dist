<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\AST;

use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
interface ASTNodeDuplicatorServiceInterface
{
    /**
     * @param Fragment[] $fragments
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference $fragmentReference
     */
    public function getExclusiveFragment($fragmentReference, $fragments) : ?Fragment;
}
