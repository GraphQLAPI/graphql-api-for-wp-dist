<?php

/**
 * Date: 16.11.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */
namespace GraphQLByPoP\GraphQLParser\Exception\Parser;

use GraphQLByPoP\GraphQLParser\Exception\Interfaces\LocationableExceptionInterface;
use GraphQLByPoP\GraphQLParser\Parser\Location;
abstract class AbstractParserError extends \Exception implements \GraphQLByPoP\GraphQLParser\Exception\Interfaces\LocationableExceptionInterface
{
    /** @var Location */
    private $location;
    public function __construct($message, \GraphQLByPoP\GraphQLParser\Parser\Location $location)
    {
        parent::__construct($message);
        $this->location = $location;
    }
    public function getLocation()
    {
        return $this->location;
    }
}
