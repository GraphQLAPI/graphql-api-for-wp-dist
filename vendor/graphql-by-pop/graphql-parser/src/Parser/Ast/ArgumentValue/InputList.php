<?php

/**
 * Date: 01.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */
namespace GraphQLByPoP\GraphQLParser\Parser\Ast\ArgumentValue;

use GraphQLByPoP\GraphQLParser\Parser\Ast\AbstractAst;
use GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces\ValueInterface;
use GraphQLByPoP\GraphQLParser\Parser\Location;
class InputList extends \GraphQLByPoP\GraphQLParser\Parser\Ast\AbstractAst implements \GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces\ValueInterface
{
    protected $list = [];
    /**
     * @param array    $list
     * @param Location $location
     */
    public function __construct(array $list, \GraphQLByPoP\GraphQLParser\Parser\Location $location)
    {
        parent::__construct($location);
        $this->list = $list;
    }
    /**
     * @return array
     */
    public function getValue()
    {
        return $this->list;
    }
    /**
     * @param array $value
     */
    public function setValue($value)
    {
        $this->list = $value;
    }
}
