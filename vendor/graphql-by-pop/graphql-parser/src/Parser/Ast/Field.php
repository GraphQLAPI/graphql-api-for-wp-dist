<?php

/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */
namespace GraphQLByPoP\GraphQLParser\Parser\Ast;

use GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces\FieldInterface;
use GraphQLByPoP\GraphQLParser\Parser\Location;
class Field extends \GraphQLByPoP\GraphQLParser\Parser\Ast\AbstractAst implements \GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces\FieldInterface
{
    use AstArgumentsTrait;
    use AstDirectivesTrait;
    /** @var string */
    private $name;
    /** @var null|string */
    private $alias = null;
    /**
     * @param string   $name
     * @param string   $alias
     * @param array    $arguments
     * @param array    $directives
     * @param Location $location
     */
    public function __construct($name, $alias, array $arguments, array $directives, \GraphQLByPoP\GraphQLParser\Parser\Location $location)
    {
        parent::__construct($location);
        $this->name = $name;
        $this->alias = $alias;
        $this->setArguments($arguments);
        $this->setDirectives($directives);
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
    /**
     * @return null|string
     */
    public function getAlias()
    {
        return $this->alias;
    }
    /**
     * @param null|string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }
    public function hasFields()
    {
        return \false;
    }
    public function getFields()
    {
        return [];
    }
}
