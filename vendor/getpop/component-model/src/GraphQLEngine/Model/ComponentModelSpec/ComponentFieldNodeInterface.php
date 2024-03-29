<?php

declare (strict_types=1);
namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
interface ComponentFieldNodeInterface
{
    public function getField() : FieldInterface;
    /**
     * A Field that appears earlier in the GraphQL query
     * must be resolved first.
     * @param \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ComponentFieldNodeInterface $againstComponentFieldNode
     */
    public function sortAgainst($againstComponentFieldNode) : int;
}
