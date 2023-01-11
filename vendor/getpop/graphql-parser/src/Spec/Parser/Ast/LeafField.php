<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

class LeafField extends \PoP\GraphQLParser\Spec\Parser\Ast\AbstractField
{
    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     *
     * Watch out: `{ title: title }` is equivalent to `{ title }`
     *
     * @see https://spec.graphql.org/draft/#sec-Field-Selection-Merging
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\LeafField $leafField
     */
    public function isEquivalentTo($leafField) : bool
    {
        return $this->doIsEquivalentTo($leafField);
    }
}
