<?php

declare (strict_types=1);
namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
class LeafComponentFieldNode extends \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\AbstractComponentFieldNode
{
    /**
     * Retrieve a new instance with all the properties from the LeafField
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\LeafField $leafField
     */
    public static function fromLeafField($leafField) : self
    {
        return new self($leafField);
    }
}
