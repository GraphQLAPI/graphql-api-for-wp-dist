<?php

/**
 * Date: 16.11.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */
namespace GraphQLByPoP\GraphQLParser\Parser\Ast;

use GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces\LocatableInterface;
use GraphQLByPoP\GraphQLParser\Parser\Location;
abstract class AbstractAst implements \GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces\LocatableInterface
{
    /** @var  Location */
    private $location;
    public function __construct(\GraphQLByPoP\GraphQLParser\Parser\Location $location)
    {
        $this->location = $location;
    }
    public function getLocation()
    {
        return $this->location;
    }
    public function setLocation(\GraphQLByPoP\GraphQLParser\Parser\Location $location)
    {
        $this->location = $location;
    }
}
