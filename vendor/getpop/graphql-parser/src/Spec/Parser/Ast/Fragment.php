<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;
class Fragment extends \PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst implements \PoP\GraphQLParser\Spec\Parser\Ast\WithDirectivesInterface, \PoP\GraphQLParser\Spec\Parser\Ast\WithFieldsOrFragmentBondsInterface
{
    use \PoP\GraphQLParser\Spec\Parser\Ast\WithDirectivesTrait;
    use \PoP\GraphQLParser\Spec\Parser\Ast\WithFieldsOrFragmentBondsTrait;
    /**
     * @readonly
     * @var string
     */
    protected $name;
    /**
     * @readonly
     * @var string
     */
    protected $model;
    /**
     * @param Directive[] $directives
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     */
    public function __construct(string $name, string $model, array $directives, array $fieldsOrFragmentBonds, Location $location)
    {
        $this->name = $name;
        $this->model = $model;
        parent::__construct($location);
        $this->setDirectives($directives);
        $this->setFieldsOrFragmentBonds($fieldsOrFragmentBonds);
    }
    protected function doAsQueryString() : string
    {
        // Generate the string for directives
        $strFragmentDirectives = '';
        if ($this->directives !== []) {
            $strDirectives = [];
            foreach ($this->directives as $directive) {
                $strDirectives[] = $directive->asQueryString();
            }
            $strFragmentDirectives = \sprintf(' %s', \implode(' ', $strDirectives));
        }
        // Generate the string for the body of the fragment
        $strFragmentFieldsOrFragmentBonds = '';
        if ($this->fieldsOrFragmentBonds !== []) {
            $strFieldsOrFragmentBonds = [];
            foreach ($this->fieldsOrFragmentBonds as $fieldsOrFragmentBond) {
                $strFieldsOrFragmentBonds[] = $fieldsOrFragmentBond->asQueryString();
            }
            $strFragmentFieldsOrFragmentBonds = \sprintf(' %s ', \implode(' ', $strFieldsOrFragmentBonds));
        }
        return \sprintf('fragment %s on %s%s {%s}', $this->name, $this->model, $strFragmentDirectives, $strFragmentFieldsOrFragmentBonds);
    }
    protected function doAsASTNodeString() : string
    {
        return \sprintf('fragment %s on %s { ... }', $this->name, $this->model);
    }
    public function getName() : string
    {
        return $this->name;
    }
    public function getModel() : string
    {
        return $this->model;
    }
}
