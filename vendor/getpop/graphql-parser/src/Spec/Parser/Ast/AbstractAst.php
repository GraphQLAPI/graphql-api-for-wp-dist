<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Query\GraphQLQueryStringFormatterInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;
abstract class AbstractAst implements \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface
{
    use StandaloneServiceTrait;
    /**
     * @var string|null
     */
    protected $queryString;
    /**
     * @var string|null
     */
    protected $astNodeString;
    /**
     * @var \PoP\GraphQLParser\Query\GraphQLQueryStringFormatterInterface|null
     */
    private $graphQLQueryStringFormatter;
    /**
     * @readonly
     * @var \PoP\GraphQLParser\Spec\Parser\Location
     */
    protected $location;
    /**
     * @param \PoP\GraphQLParser\Query\GraphQLQueryStringFormatterInterface $graphQLQueryStringFormatter
     */
    public final function setGraphQLQueryStringFormatter($graphQLQueryStringFormatter) : void
    {
        $this->graphQLQueryStringFormatter = $graphQLQueryStringFormatter;
    }
    protected final function getGraphQLQueryStringFormatter() : GraphQLQueryStringFormatterInterface
    {
        /** @var GraphQLQueryStringFormatterInterface */
        return $this->graphQLQueryStringFormatter = $this->graphQLQueryStringFormatter ?? InstanceManagerFacade::getInstance()->getInstance(GraphQLQueryStringFormatterInterface::class);
    }
    public function __construct(Location $location)
    {
        $this->location = $location;
    }
    public function __toString() : string
    {
        return $this->asQueryString();
    }
    public function getLocation() : Location
    {
        return $this->location;
    }
    public final function asQueryString() : string
    {
        if ($this->queryString === null) {
            $this->queryString = $this->doAsQueryString();
        }
        return $this->queryString;
    }
    protected abstract function doAsQueryString() : string;
    public final function asASTNodeString() : string
    {
        if ($this->astNodeString === null) {
            $this->astNodeString = $this->doAsASTNodeString();
        }
        return $this->astNodeString;
    }
    protected abstract function doAsASTNodeString() : string;
}
