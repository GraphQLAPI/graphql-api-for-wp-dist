<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;
class Directive extends \PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst implements \PoP\GraphQLParser\Spec\Parser\Ast\WithNameInterface, \PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface
{
    use \PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsTrait;
    /**
     * @readonly
     * @var string
     */
    protected $name;
    /**
     * @param Argument[] $arguments
     */
    public function __construct(string $name, array $arguments, Location $location)
    {
        $this->name = $name;
        parent::__construct($location);
        $this->setArguments($arguments);
    }
    protected function doAsQueryString() : string
    {
        $strDirectiveArguments = '';
        if ($this->arguments !== []) {
            $strArguments = [];
            foreach ($this->arguments as $argument) {
                $strArguments[] = $argument->asQueryString();
            }
            $strDirectiveArguments = \sprintf('(%s)', \implode(', ', $strArguments));
        }
        return \sprintf('@%s%s', $this->name, $strDirectiveArguments);
    }
    protected function doAsASTNodeString() : string
    {
        $strDirectiveArguments = '';
        if ($this->arguments !== []) {
            $strArguments = [];
            foreach ($this->arguments as $argument) {
                $strArguments[] = $argument->asQueryString();
            }
            $strDirectiveArguments = \sprintf('(%s)', \implode(', ', $strArguments));
        }
        return \sprintf('@%s%s', $this->name, $strDirectiveArguments);
    }
    public function getName() : string
    {
        return $this->name;
    }
    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    public function isEquivalentTo($directive) : bool
    {
        if ($this->getName() !== $directive->getName()) {
            return \false;
        }
        /**
         * Compare arguments
         */
        $thisArguments = $this->getArguments();
        $againstArguments = $directive->getArguments();
        $argumentCount = \count($thisArguments);
        if ($argumentCount !== \count($againstArguments)) {
            return \false;
        }
        /**
         * The order of the arguments does not matter.
         * These 2 fields are equivalent:
         *
         *   ```
         *   {
         *     id @strTranslate(from: "en", to: "es")
         *     id @strTranslate(to: "es", from: "en")
         *   }
         *   ```
         *
         * So first sort them as to compare apples to apples.
         */
        \usort($thisArguments, function (\PoP\GraphQLParser\Spec\Parser\Ast\Argument $argument1, \PoP\GraphQLParser\Spec\Parser\Ast\Argument $argument2) : int {
            return $argument1->getName() <=> $argument2->getName();
        });
        \usort($againstArguments, function (\PoP\GraphQLParser\Spec\Parser\Ast\Argument $argument1, \PoP\GraphQLParser\Spec\Parser\Ast\Argument $argument2) : int {
            return $argument1->getName() <=> $argument2->getName();
        });
        for ($i = 0; $i < $argumentCount; $i++) {
            $thisArgument = $thisArguments[$i];
            $againstArgument = $againstArguments[$i];
            if (!$thisArgument->isEquivalentTo($againstArgument)) {
                return \false;
            }
        }
        return \true;
    }
}
