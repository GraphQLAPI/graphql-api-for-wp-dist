<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\AST;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
interface ASTHelperServiceInterface
{
    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     * @return FieldInterface[]
     */
    public function getAllFieldsFromFieldsOrFragmentBonds($fieldsOrFragmentBonds, $fragments) : array;
    /**
     * @param Fragment[] $fragments
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $thisField
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $oppositeField
     */
    public function isFieldEquivalentToField($thisField, $oppositeField, $fragments) : bool;
}
