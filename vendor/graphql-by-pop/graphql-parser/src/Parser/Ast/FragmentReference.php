<?php

/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */
namespace GraphQLByPoP\GraphQLParser\Parser\Ast;

use GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces\FragmentInterface;
use GraphQLByPoP\GraphQLParser\Parser\Location;
class FragmentReference extends \GraphQLByPoP\GraphQLParser\Parser\Ast\AbstractAst implements \GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces\FragmentInterface
{
    /** @var  string */
    protected $name;
    /**
     * @param string   $name
     * @param Location $location
     */
    public function __construct($name, \GraphQLByPoP\GraphQLParser\Parser\Location $location)
    {
        parent::__construct($location);
        $this->name = $name;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
