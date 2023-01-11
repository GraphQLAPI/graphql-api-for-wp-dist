<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;
class FragmentReference extends \PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst implements \PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface
{
    /**
     * @readonly
     * @var string
     */
    protected $name;
    public function __construct(string $name, Location $location)
    {
        $this->name = $name;
        parent::__construct($location);
    }
    protected function doAsQueryString() : string
    {
        return \sprintf('...%s', $this->name);
    }
    protected function doAsASTNodeString() : string
    {
        return \sprintf('...%s', $this->name);
    }
    public function getName() : string
    {
        return $this->name;
    }
}
